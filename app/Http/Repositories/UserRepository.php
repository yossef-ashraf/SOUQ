<?php
namespace App\Http\Repositories;

use App\Models\User;
use App\Rules\email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Interfaces\UserInterface;
use Illuminate\Support\Facades\Validator;


class UserRepository implements UserInterface
{
use ApiResponseTrait;


public function users()
{

$admin=User::where('id',auth()->user()->id)->first();

if ($admin->auth == 'admin' )
{
$arr=User::get();
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
$User= User::where( 'id' , $request->id )->first();
unlink(public_path('images/users'.explode('/users',$User->img)[1]));
$User->delete();
return $this->apiResponse(200, 'delete user is done');
}


return $this->apiResponse(400,"you not admin ");
}





public function updateuser($request)
{
$validations = Validator::make($request->all(),[
'first_name' => 'required|min:3',
'last_name' => 'required|min:3',
'email' => 'required|email:rfc,dns',
'password' => 'required|min:8',
'street_adress' => 'required|min:5',
'city' => 'required|min:3',
'country' => 'required|min:3',
'phone' => 'required|min:11'
]);
if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

try{
$request=$request;
// dd($request);
DB::transaction(function()use($request){
$User= User::where('id', auth()->user()->id)->first();
$email= User::where('email', $request->email)->doesntExist();

if ($request->email == $User->email || $email) {
    $User->update([
'id' => auth()->user()->id,
'first_name' => $request->first_name,
'last_name' => $request->last_name,
'email' => $request->email,
'password' => Hash::make($request->password),
'street_adress' => $request->street_adress,
'city' => $request->city,
'country' => $request->country,
'phone' => $request->phone,
'auth' =>auth()->user()->auth,
'updated_at	' => time()
]);
}else{
return $this->apiResponse(400, 'validation error', "email: The email is exist.");
}

if ($request->img->extension() == "png" || $request->img->extension() == "jpg" || $request->img->extension() == "jpeg")
{
unlink(public_path('images/users'.explode('/users',$User->img)[1]));
$imagePath = time() . '_user.' . $request->img->extension();
$request->img->move(public_path('images/users'), $imagePath);
# code...
$User->update([
    'img'=>asset('images/users/'.$imagePath)
    ]);
}else {
# code...
return $this->apiResponse(400, 'validation error', $request->img->extension().'not supported');
}
});
return $this->apiResponse(200, 'account was update.');
} catch (\Exception $th) {
return $this->apiResponse(400, 'catch error', $th->getMessage() );
}



}





public function updateuserByAdmin($request)
{
if (auth()->user()->auth == 'admin' )
{

$validations = Validator::make($request->all(),[
'id' => 'required|exists:users,id',
'first_name' => 'required|min:3',
'last_name' => 'required|min:3',
'email' => 'required|email:rfc,dns',
//'password' => 'required|min:8',
'street_adress' => 'required|min:5',
'city' => 'required|min:3',
'country' => 'required|min:3',
'phone' => 'required|min:11',
'auth' => 'required'
]);
if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}


try{
    $request=$request;
    // dd($request);
    DB::transaction(function()use($request){
    $User= User::where('id', $request->id)->first();
    $email= User::where('email', $request->email)->doesntExist();

    if ($request->email == $User->email || $email) {
        $User->update([
    'id' => $request->id,
    'first_name' => $request->first_name,
    'last_name' => $request->last_name,
    'email' => $request->email,
    //'password' => Hash::make($request->password),
    'street_adress' => $request->street_adress,
    'city' => $request->city,
    'country' => $request->country,
    'phone' => $request->phone,
    'auth' =>$request->auth,
    'updated_at	' => time()
    ]);
    }else{
    return $this->apiResponse(400, 'validation error', "email: The email is exist.");
    }

    if ($request->img->extension() == "png" || $request->img->extension() == "jpg" || $request->img->extension() == "jpeg")
    {
    unlink(public_path('images/users'.explode('/users',$User->img)[1]));
    $imagePath = time() . '_user.' . $request->img->extension();
    $request->img->move(public_path('images/users'), $imagePath);
    # code...
    $User->update([
        'img'=>asset('images/users/'.$imagePath)
        ]);
    }else {
    # code...
    return $this->apiResponse(400, 'validation error', $request->img->extension().'not supported');
    }
    });
    return $this->apiResponse(200, 'account was update.');
    } catch (\Exception $th) {
    return $this->apiResponse(400, 'catch error', $th->getMessage() );
    }



    }


}












}

