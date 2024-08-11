<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Models\UserOtp;
use App\Models\UserWallet;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validations = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
            'phone'=> 'required',
            'age'=> 'required',
            'gender'=> 'required',
        ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, __('lang.validationError'), $validations->errors());
        }

        $User = null;
        DB::transaction(function () use (&$User, $request) {
            $User = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone'=> $request->phone,
                'age'=> $request->age,
                'gender'=> $request->gender,
            ]);
            $User->assignRole('user');
            UserWallet::create([
                'user_id' => $User->id,
                'wallet' => 0,
            ]);
        });

        $credentials = $request->only('email', 'password');
        $token = auth()->attempt($credentials);
        return $this->respondWithToken($token, $User);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = auth()->attempt($credentials)) {
            return $this->apiResponse(400, __('lang.notFound'));
        }
        $User = Auth::user();
        return $this->respondWithToken($token, $User);
    }

    public function logout()
    {
        Auth::logout();
        return $this->apiResponse(200, __('lang.Successfully'));
    }

    public function me()
    {
        $auth = User::where('id', auth()->user()->id)->with('wallet')->first();
        return $this->apiResponse(200, __('lang.Successfully'), null, $auth);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh(), auth()->user());
    }

    public function verifying()
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        $user->sendEmailVerificationNotification();
        return $this->apiResponse(200, __('lang.Successfully'));
    }

    public function verifys($id, Request $request)
    {
        if (!$request->hasValidSignature()) {
            return $this->apiResponse(401, __('lang.validationError'), 'Invalid/Expired URL provided.');
        }
        $user = User::findOrFail($id);
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        // يمكنك هنا تنفيذ أي إجراءات إضافية بعد التحقق
    }

    public function resend()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return $this->apiResponse(401, __('lang.validationError'), 'Email already verified.');
        }
        auth()->user()->sendEmailVerificationNotification();
        return $this->apiResponse(200, __('lang.Successfully'), null, "Email verification link sent on your email id");
    }

    public function update(Request $request)
    {
        $validations = Validator::make($request->all(), [
            'email' => ['required', 'email:rfc,dns', Rule::unique('users')->ignore(Auth::user()->id)],
            'name' => 'required|min:3',
            'phone'=> 'required',
            'age'=> 'required',
            'gender'=> 'required',
        ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, __('lang.validationError'), $validations->errors());
        }

        $User = User::where('id', auth()->user()->id)->first();
        $User->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone'=> $request->phone,
            'age'=> $request->age,
            'gender'=> $request->gender,
        ]);

        return $this->apiResponse(200, __('lang.Successfully'), null, $User);
    }

    public function change_password(Request $request)
    {
        $validations = Validator::make($request->all(), [
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8',
            'confirm_password' => "required|same:new_password",
        ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, __('lang.validationError'), $validations->errors());
        }

        $User = User::where('id', auth()->user()->id)->first();
        if (Hash::check($request->old_password, $User->password)) {
            $User->update(['password' => Hash::make($request->confirm_password)]);
            return $this->apiResponse(200, __('lang.Successfully'), null, $User);
        } else {
            return $this->apiResponse(400, __('lang.validationError'), 'Old password not correct');
        }
    }

    public function forget_password(Request $request)
    {
        $validations = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, __('lang.validationError'), $validations->errors());
        }

        $otp = rand(100000, 999999);
        $user = User::where('email', $request->email)->first();

        // إنشاء سجل UserOtp جديد
        UserOtp::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expire_at' => now()->addMinutes(30), // تعيين وقت انتهاء الصلاحية هنا
        ]);

        // إرسال البريد الإلكتروني والاستجابة
        // Mail::to($request->email)->send(new OTPMail($otp));

        return $this->apiResponse(200, __('lang.Successfully'));
    }

    public function check_otp(Request $request)
    {
        $validations = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric',
        ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, __('lang.validationError'), $validations->errors());
        }

        // العثور على المستخدم باستخدام البريد الإلكتروني
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->apiResponse(404, __('lang.notFound'));
        }

        // العثور على سجل OTP المطابق للبريد الإلكتروني
        $otpRecord = UserOtp::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->where('expire_at', '>', now()) // التحقق من أن الرمز OTP لا يزال صالحًا
            ->first();

        if (!$otpRecord) {
            return $this->apiResponse(400, __('lang.notFound'));
        }

        // إذا تم التحقق بنجاح من الرمز OTP، يمكنك تنفيذ الإجراءات الإضافية هنا
        $token = Str::random(40);
        $user->update(['remember_token' => $token]);

        // يمكنك حذف سجل OTP بعد استخدامه إذا لزم الأمر
        $otpRecord->delete();

        return $this->apiResponse(200, __('lang.Successfully'), $token);
    }

    public function check_forget_password(Request $request)
    {
        $validations = Validator::make($request->all(), [
            'token' => 'required',
            'new_password' => ['required', 'min:8'],
            'confirm_password' => "required|same:new_password",
        ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, __('lang.validationError'), $validations->errors());
        }

        $User = User::where('remember_token', $request->token)->first();

        if ($User) {
            $User->update([
                'password' => Hash::make($request->confirm_password),
                'remember_token' => null, // إبطال remember_token بعد الاستخدام
            ]);
            $token = Auth::attempt(['email' => $User->email, 'password' => $request->confirm_password]);
            $User = Auth::user();
            return $this->respondWithToken($token, $User);
        } else {
            return $this->apiResponse(404, __('lang.notFound'));
        }
    }
    protected function respondWithToken($token, $User)
    {
        $return=[
        'User' => $User,
        'access_token' => $token,
        'expires_in' => auth()->factory()->getTTL() * 60
        ];
        return $this->apiResponse(200,__('lang.Successfully'),null,$return);
    }

}
