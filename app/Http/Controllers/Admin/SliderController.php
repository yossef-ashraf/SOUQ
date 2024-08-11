<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    public function show()
    {
        $Sliders = Slider::orderBy('id', 'DESC')->get();
        return $this->apiResponse(200, __('lang.Successfully'), null, $Sliders);
    }

    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:sliders,id',
            ]);
            if ($validator->fails()) {
            return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
            }
        $Slider = Slider::findOrFail($request->id);

        return $this->apiResponse(200, __('lang.Successfully'), null, $Slider);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }

        $Slider = Slider::create([
            'image'   =>$this->AddFileInPublic('Images','Slider',$request->image),
        ]);


        return $this->apiResponse(200, __('lang.Successfully'), null, $Slider);
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:sliders,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Slider = Slider::findOrFail($request->id);
        $Slider->delete();

        return $this->apiResponse(200, __('lang.Successfully'));
    }

}
