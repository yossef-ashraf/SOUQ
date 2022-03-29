<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Interfaces\ComplaintInterface;

class Complaintcontroller extends Controller
{

public $ComplaintInterface;
public function __construct(ComplaintInterface $ComplaintInterface)
{
$this->ComplaintInterface = $ComplaintInterface;
}


public function complaints()
{
return $this->ComplaintInterface->complaints();
}

public function userComplaints()
{
return $this->ComplaintInterface->userComplaints();
}

public function addToComplaint(Request $request)
{
return $this->ComplaintInterface->addToComplaint($request);
}

public function deleteFromComplaintByAdmin(Request $request)
{
return $this->ComplaintInterface->deleteFromComplaintByAdmin($request);
}

public function deleteFromComplaint(Request $request)
{
return $this->ComplaintInterface->deleteFromComplaint($request);
}



}
