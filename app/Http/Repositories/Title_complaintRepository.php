<?php
namespace App\Http\Repositories;

use App\Models\Title_complaint;
use App\Models\complaint;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Interfaces\Title_complaintInterface;
use Illuminate\Support\Facades\Validator;


class Title_complaintRepository implements Title_complaintInterface
{
use ApiResponseTrait;

public function Title_complaints(){
$arr=Title_complaint::get();
return $this->apiResponse(200,"Title_complaints",null,$arr);
}

public function addToTitle_complaint($request)
{
    if (auth()->user()->auth == 'admin')
    {
        $validations = Validator::make($request->all(), [
            'title' => 'required|min:3'
        ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, 'validation error', $validations->errors());
        }
        Title_complaint::create([
            'title' => $request->title,
        ]);
        return $this->apiResponse(200, 'added order');
    }
}

public function updateTitle_complaintByAdmin($request){
    if (auth()->user()->auth == 'admin')
    {
        $validations = Validator::make($request->all(), [
            'id' => 'required|exists:Title_complaints,id',
            'title' => 'required'
        ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, 'validation error', $validations->errors());
        }

        $Title_complaint = Title_complaint::where([['id', $request->id]])->first();
        $Title_complaint->update([
            'title' => $request->title,
            'updated_at	' => time()
        ]);
        return $this->apiResponse(200, "update Done");
    }
}

public function deleteFromTitle_complaintByAdmin($request){
    if (auth()->user()->auth == 'admin')
    {
        $validations = Validator::make($request->all(), [
            'id' => 'required|exists:Title_complaints,id',
        ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, 'validation error', $validations->errors());
        }

        $Title_complaint = Title_complaint::where('id', $request->id)->first();
        $Title_complaint->delete();
        return $this->apiResponse(200, "delete Done");
    }
}


}
