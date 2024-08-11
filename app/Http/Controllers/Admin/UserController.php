<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\UserWallet;

use Illuminate\Http\Request;
use App\Http\Traits\FileTrait;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function show()
    {
     $User= User::with('wallet')->get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$User);
    }
    public function showAll()
    {
        $User=  User::withTrashed()->with('wallet')->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$User);
    }
    public function showTrashed()
    {
        $User= User::onlyTrashed()->with('wallet')->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$User);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $User = User::withTrashed()->with('wallet','wallet.wallet_histories')->findOrFail($request->id);
        return $this->apiResponse(200,__('lang.Successfully'),null,$User);
    }

    public function store(Request $request)
    {
        $validations = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
            'phone'=> 'required',
            'age'=> 'required',
            'gender'=> 'required',
            'roles' => 'required'
        ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, __('lang.validationError'), $validations->errors());
        }
        $User =null ;
        DB::transaction(function () use(&$User,$request) {
            $User = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone'=> $request->phone,
                'age'=> $request->age,
                'gender'=> $request->gender,
            ]);
            $User->assignRole($request->input('roles'));
            UserWallet::create([
                'user_id' => $User->id,
                'wallet' => 0,
            ]);
        });


        return $this->apiResponse(200,__('lang.Successfully'),null,$User);
    }
    public function update(Request $request)
    {
           $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'name' => 'required|string',
            'phone'=> 'required',
            'age'=> 'required',
            'gender'=> 'required',
            'email' => ['required','email:rfc,dns',Rule::unique('users')->ignore($request->id)],
            'roles' => 'required'
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());
        }
        $User = User::withTrashed()->findOrFail($request->id);
        $User->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone'=> $request->phone,
                'age'=> $request->age,
                'gender'=> $request->gender,
        ]);
            $User->assignRole($request->input('roles'));
        return $this->apiResponse(200,__('lang.Successfully'),null,$User);
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());
        }
        $User = User::withTrashed()->findOrFail($request->id);
        $User->delete();
        return $this->apiResponse(200,__('lang.Successfully'));

    }
    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());
        }
        $User = User::onlyTrashed()->findOrFail($request->id);
        $User->restore();
        return $this->apiResponse(200,__('lang.Successfully'),null,$User);
    }
    public function forceDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());
        }
        $User = User::withTrashed()->findOrFail($request->id);
        $User->forceDelete();
        return $this->apiResponse(200,__('lang.Successfully'));
    }

}
