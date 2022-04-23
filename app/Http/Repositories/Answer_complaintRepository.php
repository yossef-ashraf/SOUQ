<?php
namespace App\Http\Repositories;

use App\Models\Answer_complaint;
use App\Models\complaint;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Interfaces\Answer_complaintInterface;
use Illuminate\Support\Facades\Validator;


class Answer_complaintRepository implements Answer_complaintInterface
{
use ApiResponseTrait;

public function Answer_complaints(){
if (auth()->user()->auth == 'admin')
{
$arr=Answer_complaint::with('user','complaint')->get();
return $this->apiResponse(200,"Answer_complaints",null,$arr);
}
$arr=Answer_complaint::where('user_id', auth()->user()->id)->with('complaint')->get();
return $this->apiResponse(200,"Answer_complaints",null,$arr);
}

public function addToAnswer_complaint($request)
{
    if (auth()->user()->auth == 'admin')
    {
        $validations = Validator::make($request->all(), [
            'complaint_id' => 'required|exists:complaints,id',
            'answer' => 'required'
        ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, 'validation error', $validations->errors());
        }
        $Complaint=Complaint::where('user_id', auth()->user()->id )->first();
        Answer_complaint::create([
            'user_id' => $Complaint->id,
            'complaint_id' => $request->complaint_id,
            'answer' => $request->answer,
        ]);
        return $this->apiResponse(200, 'added order');
    }
}

public function updateAnswer_complaintByAdmin($request){
    if (auth()->user()->auth == 'admin')
    {
        $validations = Validator::make($request->all(), [
            'id' => 'required|exists:Answer_complaints,id',
            'answer' => 'required'
        ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, 'validation error', $validations->errors());
        }

        $Answer_complaint = Answer_complaint::where([['id', $request->id]])->first();
        $Answer_complaint->update([
            'answer' => $request->answer,
            'updated_at	' => time()
        ]);
        return $this->apiResponse(200, "update Done");
    }
}

public function deleteFromAnswer_complaintByAdmin($request){
    if (auth()->user()->auth == 'admin')
    {
        $validations = Validator::make($request->all(), [
            'id' => 'required|exists:Answer_complaints,id',
        ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, 'validation error', $validations->errors());
        }

        $Answer_complaint = Answer_complaint::where('id', $request->id)->first();
        $Answer_complaint->delete();
        return $this->apiResponse(200, "delete Done");
    }
}


}
