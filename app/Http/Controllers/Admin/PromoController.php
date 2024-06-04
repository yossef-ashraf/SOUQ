<?php

namespace App\Http\Controllers\Admin;

use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PromoController extends Controller
{
    public function show()
    {
     $Promo= Promo::get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Promo);
    }
    public function showAll()
    {
        $Promo=  Promo::withTrashed()->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Promo);
    }
    public function showTrashed()
    {
        $Promo= Promo::onlyTrashed()->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Promo);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:promos,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Promo = Promo::withTrashed()->findOrFail($request->id);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Promo);
    }

    public function store(Request $request)
    {
        $validations = Validator::make($request->all(), [
            'name'=>       'required|string',
            'value'=>      'required|string',
            'promo'=>      'required|unique:promos,promo',
            'expires_at'=> 'required|date',
            'type'=>       'required|in:fixed,percentage',
            'desc'=>       'string',
            'min_value'=> 'numeric',
            'max_value'=> 'numeric',
        ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, __('lang.validationError'), $validations->errors());
        }
        $Promo =null ;
        DB::transaction(function () use(&$Promo,$request) {
            $Promo = Promo::create([
                'name'=>$request->name ,
                'value'=>$request->value ,
                'promo'=>$request->promo,
                'expires_at'=>$request->expires_at ,
                'type'=>$request->type ,
                'desc'=>$request->desc ,
                'min_value'=>$request->min_value ,
                'max_value'=>$request->max_value ,

            ]);

        });
        return $this->apiResponse(200,__('lang.Successfully'),null,$Promo);
    }
    public function update(Request $request)
    {
           $validator = Validator::make($request->all(), [
            'id' => 'required|exists:promos,id',
            'name'=>       'required|string',
            'value'=>      'required|string',
            'promo'=>      ['required',Rule::unique('promos')->ignore($request->id)],
            'expires_at'=> 'required|date',
            'type'=>       'required|in:fixed,percentage',
            'desc'=>       'string',
            'min_value'=> 'numeric',
            'max_value'=> 'numeric',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());
        }
        $Promo = Promo::withTrashed()->findOrFail($request->id);
        $Promo->update([
            'name'=>$request->name ,
            'value'=>$request->value ,
            'promo'=>$request->promo,
            'expires_at'=>$request->expires_at ,
            'type'=>$request->type ,
            'desc'=>$request->desc ,
            'min_value'=>$request->min_value ,
            'max_value'=>$request->max_value ,
        ]);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Promo);
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:promos,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());
        }
        $Promo = Promo::withTrashed()->findOrFail($request->id);
        $Promo->delete();
        return $this->apiResponse(200,__('lang.Successfully'));

    }
    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:promos,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());
        }
        $Promo = Promo::onlyTrashed()->findOrFail($request->id);
        $Promo->restore();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Promo);
    }
    public function forceDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:promos,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());
        }
        $Promo = Promo::withTrashed()->findOrFail($request->id);
        $Promo->forceDelete();
        return $this->apiResponse(200,__('lang.Successfully'));
    }

}
