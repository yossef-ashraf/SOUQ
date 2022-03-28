<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\UserInterface;
use App\Http\Traits\ApiResponseTrait;
use App\Rules\email;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserRepository implements UserInterface
{
use ApiResponseTrait;


public function users()
{

$admin=User::where('id',auth()->user()->id)->first();

if ($admin->auth == 'admin' )
{
$arr=User::get(); ;
return $this->apiResponse(200,"users",null,$arr);
}

return $this->apiResponse(400,"you not admin ");
}





public function deleteuser($request)
{
if (auth()->user()->auth == 'admin' )
{

$validations = Validator::make($request->all(),[
'id' => 'required|exists:users,id'
]);
// dd($request->id);
if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}
User::where('id', $request->id)->delete();
return $this->apiResponse(200, 'delete user is done');
}


return $this->apiResponse(400,"you not admin ");
}





public function updateuser($request)
{
$validations = Validator::make($request->all(),[
'name' => 'required|min:3',
'email' => ['required', new email,'email:rfc,dns'],
'password' => 'required|min:8',
'adress' => 'required|min:5',
'phone' => 'required'
]);
if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

User::update([
'id' => auth()->user()->id,
'name' => $request->name,
'email' => $request->email,
'password' => Hash::make($request->password),
'adress' => $request->adress,
'phone' => $request->phone,
'auth' =>auth()->user()->auth
]);


return $this->apiResponse(200, 'account was update');
}



public function updateuserByAdmin($request)
{
if (auth()->user()->auth == 'admin' )
{

$validations = Validator::make($request->all(),[
'id' => 'required|exists:users,id',
'name' => 'required|min:3',
'email' => ['required', new email,'email:rfc,dns'],
// 'password' => 'required|min:8',
'adress' => 'required|min:5',
'phone' => 'required',
'auth' => 'required'
]);
if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}
// dd($request);
$User= User::where( 'id' , $request->id )->first();
$User->update([
'id' => $request->id,
'name' => $request->name,
'email' => $request->email,
// 'password' => Hash::make($request->password),
'adress' => $request->adress,
'phone' => $request->phone,
'auth' =>$request->auth
]);
return $this->apiResponse(200, 'account was update');


}

}













}

