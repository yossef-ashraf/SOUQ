<?php

namespace App\Http\Controllers\Web;

use App\Models\Shipping;
use Illuminate\Http\Request;
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
        if ($validator->fails())
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());

        $Shipping= Shipping::where('id', $request->id)
        ->first();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Shipping);
    }
}
