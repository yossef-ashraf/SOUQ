<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Interfaces\Answer_complaintInterface;

class Answer_complaintcontroller extends Controller
{

public $Answer_complaintInterface;
public function __construct(Answer_complaintInterface $Answer_complaintInterface)
{
$this->Answer_complaintInterface = $Answer_complaintInterface;
}


public function Answer_complaints()
{
return $this->Answer_complaintInterface->Answer_complaints();
}

public function userAnswer_complaints(Request $request)
{
return $this->Answer_complaintInterface->userAnswer_complaints($request);
}

public function addToAnswer_complaint(Request $request)
{
return $this->Answer_complaintInterface->addToAnswer_complaint($request);
}

public function updateAnswer_complaintByAdmin(Request $request)
{
return $this->Answer_complaintInterface->updateAnswer_complaintByAdmin($request);
}

public function deleteFromAnswer_complaintByAdmin(Request $request)
{
return $this->Answer_complaintInterface->deleteFromAnswer_complaintByAdmin($request);
}



}
