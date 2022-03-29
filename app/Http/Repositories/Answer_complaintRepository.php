<?php
namespace App\Http\Repositories;

use App\Models\Answer_complaint;
use App\Models\complaint;
use App\Models\User;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Interfaces\Answer_complaintInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class Answer_complaintRepository implements Answer_complaintInterface
{
use ApiResponseTrait;

public function Answer_complaints(){
if (auth()->user()->auth == 'admin')
{
$arr=Answer_complaint::with('user','complaint')->get();
return $this->apiResponse(200,"Answer_complaints",null,$arr);
}
return $this->apiResponse(400,"you not admin");
}
//
public function userAnswer_complaints($request){
$validations = Validator::make($request->all(),[
'complaint_id' => 'required|exists:complaints,id',
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}
$arr=Answer_complaint::where([['user_id', auth()->user()->id],['complaint_id', $request->complaint_id]] )->with('user','complaint')->get();
return $this->apiResponse(200,"Answer_complaints",null,$arr);
}

//
public function addToAnswer_complaint($request){
$validations = Validator::make($request->all(),[
'complaint_id' => 'required|exists:complaints,id',
'answer' => 'required'
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

Answer_complaint::create([
'user_id' => auth()->user()->id,
'complaint_id' => $request->complaint_id,
'answer' => $request->answer,
]);
return $this->apiResponse(200, 'added order');
}


public function updateAnswer_complaintByAdmin($request){

$validations = Validator::make($request->all(),[
'id' => 'required|exists:Answer_complaints,id',
'user_id' => 'required|exists:users,id',
'complaint_id' => 'required|exists:complaints,id',
'answer' => 'required'
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

$Answer_complaint=Answer_complaint::where([['user_id', $request->id ],['id',$request->id],['complaint_id',$request->complaint_id]])->first();
$Answer_complaint->update([
    'id' => $request->id,
    'user_id' => $request->user_id,
    'complaint_id' => $request->complaint_id,
    'answer' => $request->answer,
    'updated_at	'=> time()
    ]);
return $this->apiResponse(200,"update Done");

}


public function deleteFromAnswer_complaintByAdmin($request){

$validations = Validator::make($request->all(),[
'id' => 'required|exists:Answer_complaints,id',
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

$Answer_complaint=Answer_complaint::where('id',$request->id)->first();
$Answer_complaint->delete();
return $this->apiResponse(200,"delete Done");

}


}
