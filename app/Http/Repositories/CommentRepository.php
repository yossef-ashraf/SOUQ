<?php
namespace App\Http\Repositories;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Interfaces\CommentInterface;
use Illuminate\Support\Facades\Validator;


class CommentRepository implements CommentInterface
{
use ApiResponseTrait;

public function Comments(){
if (auth()->user()->auth == 'admin')
{
$arr=Comment::with('user','product')->get();
return $this->apiResponse(200,"Comments",null,$arr);
}
return $this->apiResponse(400,"you not admin");
}

//
public function userComments(){
$arr=comment::where('user_id', auth()->user()->id )->with('user','product')->get();
return $this->apiResponse(200,"Comments",null,$arr);
}
//
public function productComments($request){
$validations = Validator::make($request->all(),[
'product_id' => 'required|exists:products,id',
]);
if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}
$arr=Comment::where('product_id',$request->product_id)->with('user','product')->get();
return $this->apiResponse(200,"Comments Done",null,$arr);
}
//

public function addToComment($request){
$validations = Validator::make($request->all(),[
'product_id' => 'required|exists:products,id',
'comments' => 'required',
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

Comment::create([
'user_id' => Auth::user()->id,
'product_id' => $request->product_id,
'comments' => $request->comments
]);
return $this->apiResponse(200, 'added order');
}


//
public function UpdateComment($request){
$validations = Validator::make($request->all(),[
'id' => 'required|exists:Comments,id',
'product_id' => 'required|exists:products,id',
'comments' => 'required',
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

$Comment=Comment::where([['user_id', auth()->user()->id ],['product_id',$request->product_id]])->first();

$Comment->update([
'_id' => $request->_id,
'user_id' => Auth::user()->id,
'product_id' => $request->product_id,
'comments' => $request->comments
]);
return $this->apiResponse(200, 'updated order');
}
//
public function deleteFromComment($request){
$validations = Validator::make($request->all(),[
'id' => 'required|exists:Comments,id',
'product_id' => 'required|exists:products,id',
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

$Comment=Comment::where([['user_id', auth()->user()->id ],['product_id',$request->product_id]])->first();
$Comment->delete();
return $this->apiResponse(200,"delete Done");
}
//
public function deleteFromCommentByAdmin($request){

$validations = Validator::make($request->all(),[
'id' => 'required|exists:Comments,id',
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

$Comment=Comment::where('id', $request->id )->first();
$Comment->delete();
return $this->apiResponse(200,"delete Done");

}


}
