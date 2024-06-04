<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function show()
    {
     $Shipping= Shipping::get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Shipping);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:shippings,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Shipping = Shipping::findOrFail($request->id);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Shipping);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required|array',
            'price'=> 'required|numeric',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Shipping = Shipping::create([
            'city'=>$request->city,
            'price'=>$request->price,
        ]);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Shipping);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'   => 'required|exists:shippings,id',
            'city' => 'required|array',
            'price'=> 'required|numeric',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Shipping = Shipping::withTrashed()->findOrFail($request->id);
        DB::transaction(function () use(&$Shipping , $request){
                $Shipping->update([
                    'city'=>$request->city,
                    'price'=>$request->price,
                ]);
        });
        return $this->apiResponse(200,__('lang.Successfully'),null,$Shipping);
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:shippings,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Shipping = Shipping::findOrFail($request->id);
        $Shipping->delete();
        return $this->apiResponse(200,__('lang.Successfully'));

    }
}
