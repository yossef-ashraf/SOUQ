<?php

namespace App\Http\Controllers\Web;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    public function show()
    {
     $Slider= Slider::get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Slider);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:sliders,id',
        ]);
        if ($validator->fails())
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());

        $Slider= Slider::where('id', $request->id)
        ->first();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Slider);
    }
}
