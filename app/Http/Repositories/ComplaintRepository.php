<?php
namespace App\Http\Repositories;

use App\Models\Complaint;
use App\Models\Title_complaint;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Interfaces\ComplaintInterface;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ComplaintRepository implements ComplaintInterface
{
use ApiResponseTrait;

public function Complaints(){
if (auth()->user()->auth == 'admin')
{
$arr=Complaint::with('user','title')->get();
return $this->apiResponse(200,"Complaints",null,$arr);
}
$arr=Complaint::with('title')->where('user_id', auth()->user()->id )->get();
return $this->apiResponse(200,"Complaints",null,$arr);
}
//
public function addToComplaint($request){
$validations = Validator::make($request->all(),[
'Complaints' => 'required',
'title_id'=> 'required|exists:title_complaints,id'
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

Complaint::create([
'user_id' => Auth::user()->id,
'complaint' => $request->Complaints,
'title_id' => $request->title_id,
'status' => 0,
'answer' => "",
]);
return $this->apiResponse(200, 'added Complaint');
}
//

public function  updateComplaint($request){

$validations = Validator::make($request->all(),[
'id'=> 'required|exists:complaints,id',
'answer' => 'required|min:2'
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}
$Complaint=Complaint::where('id',$request->id)->first();
$Complaint->update([
'status' => 1,
'answer' => $request->answer,
'updated_at	' => time()
]);
return $this->apiResponse(200, 'updateed Complaint');

}



//
public function deleteFromComplaint($request){
$validations = Validator::make($request->all(),[
'id' => 'required|exists:Complaints,id',
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}
if (auth()->user()->auth == 'admin')
{
$Complaint=Complaint::where('id',$request->id)->first();
}else {
$Complaint = Complaint::where([['user_id', auth()->user()->id], ['id', $request->id]])->first();
}
$Complaint->delete();
return $this->apiResponse(200,"delete Done");
}


}
