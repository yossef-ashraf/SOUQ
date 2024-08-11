<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function show()
    {
     $Brand= Brand::get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Brand);
    }
    public function showAll()
    {
        $Brand=  Brand::withTrashed()->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Brand);
    }
    public function showTrashed()
    {
        $Brand= Brand::onlyTrashed()->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Brand);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:brands,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Brand = Brand::withTrashed()->findOrFail($request->id);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Brand);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|array',
            'img' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'offer'=> 'numeric',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Brand = Brand::create([
            'name'=>$request->name,
            'offer'=>$request->offer,
            'img'=>$this-> AddFileInPublic('Images','Brand',$request->img),
        ]);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Brand);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'   => 'required|exists:brands,id',
            'name' => 'required|array',
            'offer'=> 'numeric',
            'img'  => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Brand = Brand::withTrashed()->findOrFail($request->id);
        DB::transaction(function () use(&$Brand , $request){
                $Brand->update([
                    'name'=>$request->name,
                    'offer'=>$request->offer,
                ]);

                if ($request->img) {
                    $Brand->update([
                        'img'=>$this-> UpdateFileInPublic('Images','Brand',$request->img , $Brand->img ),
                    ]);
                }
        });
        return $this->apiResponse(200,__('lang.Successfully'),null,$Brand);
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:brands,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Brand = Brand::findOrFail($request->id);
        $Brand->delete();
        return $this->apiResponse(200,__('lang.Successfully'));

    }
    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:brands,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Brand = Brand::onlyTrashed()->findOrFail($request->id);
        $Brand->restore();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Brand);
    }
    public function forceDelete(Request $request)
    {
        // dd('ff');
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:brands,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Brand = Brand::withTrashed()->findOrFail($request->id);
        unlink(public_path($Brand->img));
        $Brand->forceDelete();
        return $this->apiResponse(200,__('lang.Successfully'));
    }
}
