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

class ProductDetailController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'product_id' => 'required|exists:products,id',
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
        $ProductDetail= 0;
        DB::transaction(function () use($request ,&$ProductDetail){

        foreach ($request->details as $detail) {
            $ProductDetail = ProductDetail::create([
                'product_id'   =>$request->product_id,
                'color'   =>$detail['color'],
            ]);

            foreach ($detail['images'] as $image) {
                $ProductDetailImage =  ProductImage::create([
                    'product_detail_id'  =>$ProductDetail->id,
                    'image'              =>$this->AddFileInPublic('Images','ProductDetail',$image),
                ]);
            }

            foreach ($detail['size'] as $size) {
                $ProductDetailSize = ProductSize::create([
                    'product_detail_id'  =>$ProductDetail->id,
                    'size'               =>(string)$size['size'],
                    'quantity'           =>$size['quantity'],
                    'sell_price'         =>$size['sell_price'],
                    'price'              =>$size['price'],
                ]);
            }
        }
        });

        return $this->apiResponse(200,__('lang.Successfully'),null,$ProductDetail);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //ProductDetail
            'id'   => 'required|exists:product_details,id',
            'product_id' => 'required|exists:products,id',
            'color' => 'required',

            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $ProductDetail = ProductDetail::withTrashed()->findOrFail($request->id);
        DB::transaction(function () use(&$ProductDetail , $request){
                $ProductDetail->update([
                    'product_id'   =>$request->product_id,
                    'color'   =>$request->color,
                ]);
            });
        return $this->apiResponse(200,__('lang.Successfully'),null,$ProductDetail);
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:product_details,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $ProductDetail = ProductDetail::findOrFail($request->id);
        $ProductDetail->delete();
        return $this->apiResponse(200,__('lang.Successfully'));

    }
    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:product_details,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $ProductDetail = ProductDetail::onlyTrashed()->findOrFail($request->id);
        $ProductDetail->restore();
        return $this->apiResponse(200,__('lang.Successfully'),null,$ProductDetail);
    }
    public function forceDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:product_details,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $ProductDetail = ProductDetail::withTrashed()->findOrFail($request->id);
        $ProductDetail->forceDelete();
        return $this->apiResponse(200,__('lang.Successfully'));
    }




}
