<?php

namespace App\Http\Controllers\Web;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    //
    public function show()
    {
     $Blog= Blog::get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Blog);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:blogs,id',
        ]);
        if ($validator->fails())
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());

        $Blog= Blog::where('id', $request->id)
        ->with('product')
        ->first();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Blog);
    }
}
