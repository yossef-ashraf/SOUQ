<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
use ApiResponseTrait;


//////////////////
public function login(Request $request)
{
    $credentials = request(['phone', 'password']);
    if (! $token = auth()->attempt($credentials)) {

    return $this->apiResponse(400, 'login', "Unauthorized");
    }
    $user = Auth::user();
    return $this->respondWithToken($user,$token);
}
public function register(Request $request)
{
    $validations = Validator::make($request->all(),[
    'firstname' => 'required|min:3',
    'lastname' => 'required|min:3',
    'email' => 'required|string|email|max:255|unique:users',
    'phone' => 'required|numeric|unique:users',
    'password' => 'required|string|min:8',
    ]);

    if($validations->fails())
    {
    return $this->apiResponse(400, 'validation error', $validations->errors());
    }

    $user = User::create([
    'firstname' => $request->firstname,
    'lastname' => $request->lastname,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'address' => null,
    'phone' => $request->phone,
    'auth' => 'user',
    ]);

    $token = Auth::attempt(request(['phone', 'password']));

    return  $this->respondWithToken($user,$token ) ;
}
public function UpdateUser(Request $request )
{
    $validations = Validator::make($request->all(),[
        'firstname' => 'required|min:3',
        'lastname' => 'required|min:3',
        'email' =>  ['required',Rule::unique('Users')->ignore(Auth::user()->id),'string','email','max:255'],
        'phone' => ['required',Rule::unique('Users')->ignore(Auth::user()->id),'numeric'],
        'password' => 'required|string|min:8',
        'address' => 'required|min:5',
    ]);

    if($validations->fails())
    {
    return $this->apiResponse(400, 'validation error', $validations->errors());
    }

    $User= User::where('id',  Auth::user()->id )->first();
    $User->update([
        'firstname' => $request->firstname,
        'lastname' => $request->lastname,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'address' => $request->address,
        'phone' => $request->phone,
        'updated_at	' => time()
    ]);
    return $this->apiResponse(200," update user done" , $User);
}
/////////////////////
public function logout()
{
    Auth::logout();
    return $this->apiResponse(200, 'Successfully logged out');
}
public function auth()
{
    $auth=User::where('id',auth()->user()->id)->first();
    if ($auth == null) {
    return $this->apiResponse(400,"not found user");
    }
    return $this->apiResponse(200,"user",null,$auth);
}

public function forget_password(Request $request)
{
    $validations = Validator::make($request->all(), [
        'email' => 'required|email|exists:Users,email'
    ]);

    if ($validations->fails()) {
        return $this->apiResponse(400, 'validation error', $validations->errors());
    }
    $otp = rand(0000000,999999);
    $User= User::where('email',$request->email)->first();
    $User->update(['otp' => $otp ]);
    $datalis=[
        'otp' => $otp,
    ];

    Mail::to($request->email)->send(new OtpMail($datalis));
    return $this->apiResponse(200, 'Successfully');
}

public function check_otp(Request $request)
{
    $validations = Validator::make($request->all(), [
        'otp' => 'required',
    ]);

    if ($validations->fails()) {
        return $this->apiResponse(400, 'validation error', $validations->errors());
    }

    $User= User::where([['otp', $request->otp]])->first();

        if ($User) {
            $otp_token =Str::random(40);
            $User->update(['otp_token' => $otp_token ]);
            return $this->apiResponse(200, 'Successfully', null,$otp_token);
        }
        else {
            return $this->apiResponse(400, 'error', 'User Not Found');
        }

}

public function check_forget_password(Request $request)
{

        $validations = Validator::make($request->all(), [
            'otp_token' => 'required',
            'new_password' => ['required','min:8'],
            'confirm_password' => "required|same:new_password",
        ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, 'validation error', $validations->errors());
        }

        $User= User::where([['otp_token', $request->otp_token]])->first();

        if ($User) {
            $User->update(['password' => Hash::make($request->confirm_password)]);
            $token = Auth::attempt(['email' => $User->email, 'password' => $request->confirm_password]);
            $User = Auth::User();

            return $this->respondWithToken($token , $User );

        }else {
            return $this->apiResponse(400, 'error', 'User Not Found');
        }

}


//////////////////
public function User()
{
    $users=User::get();
    return $this->apiResponse(200, 'Users', null , $users);
}
public function DeleteUser(Request $request)
{
    $validated = Validator::make($request->all(),[
        'id' => 'required|exists:Users,id',
    ]);
    if($validated->fails())
    {
    return $this->apiResponse(400, 'validation error', $validated->errors());
    }

    $user= User::where( 'id' , $request->id )->first();
    $user->delete();
    return $this->apiResponse(200," delete user done");
}

public function updateAuthUser(Request $request)
{
    $validations = Validator::make($request->all(),[
        'id' => 'required|exists:Users,id',
        'auth' => 'required|min:3',
    ]);
    if($validations->fails())
    {
    return $this->apiResponse(400, 'validation error', $validations->errors());
    }

    try{
    $User= User::where('id', $request->id)->first();
    DB::transaction(function()use(&$User , $request){
    $User->update([
    'auth' => $request->auth,
    ]);
    });
    return $this->apiResponse(200, 'account was update.',null,$User);
    } catch (\Exception $th) {
    return $this->apiResponse(400, 'catch error', $th->getMessage() );
    }

}

/////////////////////////////////////////////////////////////////
protected function respondWithToken($user,$token)
{
    $return=[ 'user' => $user,
    'authorisation' => [
        'token' => $token,
        'type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 60
    ]];
    return $this->apiResponse(200,"login",null,$return);
}

}
