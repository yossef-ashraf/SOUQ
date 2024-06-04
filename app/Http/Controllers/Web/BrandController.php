<?php

namespace App\Http\Controllers\Web;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function show()
    {
     $Brand= Brand::get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Brand);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:brands,id',
        ]);
        if ($validator->fails())
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());

        $Brand= Brand::where('id', $request->id)
        ->with('products')
        ->first();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Brand);
    }
    public function showNew()
    {
     $Brand= Brand::orderBy('created_at', 'desc')->take(15)->get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Brand);
    }
    public function showOffer()
    {
     $Brand=  Brand::whereNotNull('offer')->orderBy('updated_at', 'desc')->get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Brand);
    }

}
