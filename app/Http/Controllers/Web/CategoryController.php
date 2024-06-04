<?php

namespace App\Http\Controllers\Web;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function show()
    {
     $Category= Category::get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Category);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:categories,id',
        ]);
        if ($validator->fails())
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());

        $Category= Category::where('id', $request->id)
        ->with('products')
        ->first();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Category);
    }
    public function showNew()
    {
     $Category= Category::orderBy('created_at', 'desc')->take(15)->get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Category);
    }
    public function showOffer()
    {
     $Category=  Category::whereNotNull('offer')->orderBy('updated_at', 'desc')->get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Category);
    }


}
