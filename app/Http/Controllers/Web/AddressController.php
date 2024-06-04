<?php

namespace App\Http\Controllers\Web;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function show()
    {
     $Address= Address::get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Address);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:addresses,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Address = Address::withTrashed()->findOrFail($request->id);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Address);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city'=> 'required|max:255',
            'address'=> 'required|max:255',
            'street_name'=> 'max:255',
            'building_number'=> 'numeric',
            'flat_number'=> 'numeric',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Address = Address::create([
            'city'=>$request->city,
            'address'=>$request->address,
            'street_name'=>$request->street_name,
            'building_number'=>$request->building_number,
            'flat_number'=>$request->flat_number,
            'user_id'=>auth()->user()->id,
        ]);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Address);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'   => 'required|exists:addresses,id',
            'city'=> 'required|max:255',
            'address'=> 'required|max:255',
            'street_name'=> 'max:255',
            'building_number'=> 'numeric',
            'flat_number'=> 'numeric',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Address = Address::withTrashed()->findOrFail($request->id);
        DB::transaction(function () use(&$Address , $request){
                $Address->update([
                    'city'=>$request->city,
                    'address'=>$request->address,
                    'street_name'=>$request->street_name,
                    'building_number'=>$request->building_number,
                    'flat_number'=>$request->flat_number,
                ]);
            });
        return $this->apiResponse(200,__('lang.Successfully'),null,$Address);
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:addresses,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Address = Address::findOrFail($request->id);
        $Address->delete();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Address);

    }

}
