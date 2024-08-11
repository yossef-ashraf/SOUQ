<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function show()
    {
     $Product= Product::get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Product);
    }
    public function showAll()
    {
        $Product=  Product::withTrashed()->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Product);
    }
    public function showTrashed()
    {
        $Product= Product::onlyTrashed()->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Product);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:products,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Product = Product::withTrashed()->with(['category_trash','brand_trash','blogs_trash','comments','product_details_trash','product_details_trash.product_sizes_trash','product_details_trash.product_images'])->findOrFail($request->id);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Product);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            //product
            'name' => 'required|array',
            'description' => 'required|array',
            'material' => 'required|array',
            'img' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'category_id' => 'required|exists:categories,id', // تغيير categories إلى الجدول الصحيح
            'brand_id' => 'exists:brands,id', // تغيير brands إلى الجدول الصحيح

            'general_price' => 'required|numeric',
            'discount' => 'numeric',
            'has_discount_category' => 'boolean',
            'has_discount_brand' => 'boolean',

            'details' => 'required|array',
            'details.*' => 'required|array',
            'details.*.color' => 'required',
            'details.*.images' => 'required|array',
            'details.*.images.*' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'details.*.size' => 'required|array',
            'details.*.size.*' => 'required|array',
            'details.*.size.*.size' => 'required|string',
            'details.*.size.*.sell_price' => 'required|numeric',
            'details.*.size.*.price' => 'required|numeric',
            'details.*.size.*.quantity' => 'required|numeric',

        ]);

        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Product= 0;
        DB::transaction(function () use($request ,&$Product){
        $Product = Product::create([
            'name'                    =>$request->name,
            'general_price'           =>$request->general_price,
            'description'             =>$request->description,
            'material'                =>$request->material,
            'img'                     =>$this->AddFileInPublic('Images','Product',$request->img),
            'category_id'             =>$request->category_id,
            'brand_id'                =>$request->brand_id ?? null,
            'discount'                =>$request->discount ?? null,
            'has_discount_category'   =>$request->has_discount_category ?? false,
            'has_discount_brand'      =>$request->has_discount_brand ?? false,
        ]);

        foreach ($request->details as $detail) {
            $productDetail = ProductDetail::create([
                'product_id'   =>$Product->id,
                'color'   =>$detail['color'],
            ]);
            // dump($detail['images']);
            // Store the product detail images
            foreach ($detail['images'] as $image) {
                $productDetailImage =  ProductImage::create([
                    'product_detail_id'  =>$productDetail->id,
                    'image'              =>$this->AddFileInPublic('Images','Product',$image),
                ]);
            }

            // dump('-----------');
            // dump($detail['size']);
            // Store the product detail sizes
            foreach ($detail['size'] as $size) {
                // dump($size);
                $productDetailSize = ProductSize::create([
                    'product_detail_id'  =>$productDetail->id,
                    'size'               =>$size['size'],
                    'quantity'           =>$size['quantity'],
                    'sell_price'         =>$size['sell_price'],
                    'price'              =>$size['price'],
                ]);
            }
        }
        // dd("-----");

        });

        return $this->apiResponse(200,__('lang.Successfully'),null,$Product);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //product
            'id'   => 'required|exists:products,id',
            'name' => 'required|array',
            'general_price' => 'required|numeric',
            'description' => 'required|array',
            'material' => 'required|array',
            'img' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id', // تغيير categories إلى الجدول الصحيح
            'brand_id' => 'exists:brands,id', // تغيير brands إلى الجدول الصحيح
            'discount' => 'numeric',
            'has_discount_category' => 'boolean',
            'has_discount_brand' => 'boolean',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Product = Product::withTrashed()->findOrFail($request->id);
        DB::transaction(function () use(&$Product , $request){
                $Product->update([
                    'general_price'           =>$request->general_price,
                    'name'                    =>$request->name,
                    'description'             =>$request->description,
                    'material'                =>$request->material,
                    'category_id'             =>$request->category_id,
                    'brand_id'                =>$request->brand_id ?? null,
                    'discount'                =>$request->discount ?? null,
                    'has_discount_category'   =>$request->has_discount_category ?? false,
                    'has_discount_brand'      =>$request->has_discount_brand ?? false,
                ]);

                if ($request->img) {
                    $Product->update([
                        'img'=>$this-> UpdateFileInPublic('Images','Product',$request->img , $Product->img ),
                    ]);
                }
        });
        return $this->apiResponse(200,__('lang.Successfully'),null,$Product);
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:products,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Product = Product::findOrFail($request->id);
        $Product->delete();
        return $this->apiResponse(200,__('lang.Successfully'));

    }
    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:products,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Product = Product::onlyTrashed()->findOrFail($request->id);
        $Product->restore();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Product);
    }
    public function forceDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:products,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Product = Product::withTrashed()->findOrFail($request->id);
        unlink(public_path($Product->img));
        $Product->forceDelete();
        return $this->apiResponse(200,__('lang.Successfully'));
    }




}
