<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductImageController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'product_detail_id' => 'required|exists:product_details,id',
            'images' => 'required|array',
            'images.*' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $ProductImage= 0;
        DB::transaction(function () use($request ,&$ProductImage){
            foreach ($request->images as $image) {
                $ProductImage =  ProductImage::create([
                    'product_detail_id'  =>$request->product_detail_id,
                    'image'              =>$this->AddFileInPublic('Images','ProductDetail',$image),
                ]);
            }
        });

        return $this->apiResponse(200,__('lang.Successfully'),null,$ProductImage);
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:product_images,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $ProductImage = ProductImage::withTrashed()->findOrFail($request->id);
        unlink(public_path($ProductImage->image));
        $ProductImage->forceDelete();
        return $this->apiResponse(200,__('lang.Successfully'));
    }


}
