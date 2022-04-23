<?php
namespace App\Http\Repositories;

use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Interfaces\ComplaintInterface;


class ComplaintRepository implements ComplaintInterface
{
use ApiResponseTrait;

public function Complaints(){
if (auth()->user()->auth == 'admin')
{
$arr=Complaint::with('user')->get();
return $this->apiResponse(200,"Complaints",null,$arr);
}
$arr=Complaint::where('user_id', auth()->user()->id )->get();
return $this->apiResponse(200,"Complaints",null,$arr);
}
//
public function addToComplaint($request){
$validations = Validator::make($request->all(),[
'Complaints' => 'required',
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

Complaint::create([
'user_id' => Auth::user()->id,
'complaint' => $request->Complaints,
]);
return $this->apiResponse(200, 'added order');
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
