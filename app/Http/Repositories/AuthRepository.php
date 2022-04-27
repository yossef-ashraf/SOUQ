<?php
namespace App\Http\Repositories;

use App\Models\User;
use App\Rules\email;
use App\Rules\image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Interfaces\AuthInterface;
use Illuminate\Support\Facades\Validator;


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
$validations = Validator::make($request->all(), [
'first_name' => 'required|min:3',
'last_name' => 'required|min:3',
'email' => ['required', 'unique:users,email','email:rfc,dns'],
'password' => 'required|min:8',
'street_adress' => 'required|min:5',
'city' => 'required|min:3',
'country' => 'required|min:3',
'img' => [new image],
'phone' => 'required'
]);

if ($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}


try {
    $request=$request;
    // dd($request);
    DB::transaction(function()use($request){

$User=User::create([
'first_name' => $request->first_name,
'last_name' => $request->last_name,
'email' => $request->email,
'password' => Hash::make($request->password),
'street_adress' => $request->street_adress,
'city' => $request->city,
'country' => $request->country,
'img' => asset('images/users/def_user_img.jpg'),
'phone' => $request->phone,
'auth' =>'user'
]);

if ($request->img) {
        if ($request->img->extension() == "png" || $request->img->extension() == "jpg" || $request->img->extension() == "jpeg") {
            $imagePath = time() . '_user.' . $request->img->extension();
            $request->img->move(public_path('images/users'), $imagePath);
            $User->update([
                'img' => asset('images/users/'.$imagePath)
            ]);
        } else {
            return $this->apiResponse(400, 'validation error', $request->img->extension().'not supported');
        }
    }
});

return $this->apiResponse(200, 'account was created');

} catch (\Exception $th) {
    return $this->apiResponse(400, 'catch error', $th->getMessage() );
    }


}



public function auth()
{

$user=User::where('id',auth()->user()->id)->first();

if ($user == null) {
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
