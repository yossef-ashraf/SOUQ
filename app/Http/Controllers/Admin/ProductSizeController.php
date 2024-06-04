<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductSizeController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'product_detail_id' => 'required|exists:product_details,id',
            'size' => 'required|string',
            'sell_price' => 'required|numeric',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'base_price' => 'required|numeric',

        ]);

        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $ProductSize= 0;
        DB::transaction(function () use($request ,&$ProductSize){
            $ProductSize= ProductSize::create([
                'base_price'               =>$request->base_price,
                'product_detail_id'        =>$request->product_detail_id,
                'size'                     =>$request->size,
                'quantity'                 =>$request->quantity,
                'sell_price'               =>$request->sell_price,
                'price'                    =>$request->price,
            ]);
        });

        return $this->apiResponse(200,__('lang.Successfully'),null,$ProductSize);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //ProductSize
            'id'   => 'required|exists:product_sizes,id',
            'product_detail_id' => 'required|exists:product_details,id',
            'size' => 'required|string',
            'sell_price' => 'required|numeric',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'base_price' => 'required|numeric',

            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $ProductSize = ProductSize::withTrashed()->findOrFail($request->id);
        DB::transaction(function () use(&$ProductSize , $request){
                $ProductSize->update([
                    'product_detail_id'        =>$request->product_detail_id,
                    'base_price'               =>$request->base_price,
                    'size'                     =>$request->size,
                    'quantity'                 =>$request->quantity,
                    'sell_price'               =>$request->sell_price,
                    'price'                    =>$request->price,
                ]);
            });
        return $this->apiResponse(200,__('lang.Successfully'),null,$ProductSize);
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:product_sizes,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $ProductSize = ProductSize::findOrFail($request->id);
        $ProductSize->delete();
        return $this->apiResponse(200,__('lang.Successfully'));

    }
    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:product_sizes,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $ProductSize = ProductSize::onlyTrashed()->findOrFail($request->id);
        $ProductSize->restore();
        return $this->apiResponse(200,__('lang.Successfully'),null,$ProductSize);
    }
    public function forceDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:product_sizes,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $ProductSize = ProductSize::withTrashed()->findOrFail($request->id);
        $ProductSize->forceDelete();
        return $this->apiResponse(200,__('lang.Successfully'));
    }

}
