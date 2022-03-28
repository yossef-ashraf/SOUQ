<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\AuthInterface;
use App\Http\Traits\ApiResponseTrait;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Rules\email;
use Illuminate\Http\Request;


class AuthRepository implements AuthInterface
{
use ApiResponseTrait;


public function login($request)
{

$credentials = request(['email', 'password']);
if (! $token = auth()->attempt($credentials)) {
return $this->apiResponse(400,"not found user ");
}
return $this->respondWithToken($token);
}



public function register($request)
{
// dd($request);
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

User::create([
'name' => $request->name,
'email' => $request->email,
'password' => Hash::make($request->password),
'adress' => $request->adress,
'phone' => $request->phone,
'auth' =>'user'
]);

return $this->apiResponse(200, 'account was created');
}


public function auth()
{

$a=User::where('id',auth()->user()->id)->first();

if ($a == null) {
return $this->apiResponse(400,"not found user");
}

return $this->apiResponse(200,"user",null,$a);
}







protected function respondWithToken($token)
{
$return=[
'access_token' => $token,
'expires_in' => auth()->factory()->getTTL() * 60
];
return $this->apiResponse(200,"login",null,$return);
}
}
