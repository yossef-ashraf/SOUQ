<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Interfaces\Title_complaintInterface;

class Title_complaintcontroller extends Controller
{

public $Title_complaintInterface;
public function __construct(Title_complaintInterface $Title_complaintInterface)
{
$this->Title_complaintInterface = $Title_complaintInterface;
}


public function Title_complaints()
{
return $this->Title_complaintInterface->Title_complaints();
}

public function addToTitle_complaint(Request $request)
{
return $this->Title_complaintInterface->addToTitle_complaint($request);
}

public function updateTitle_complaintByAdmin(Request $request)
{
return $this->Title_complaintInterface->updateTitle_complaintByAdmin($request);
}

public function deleteFromTitle_complaintByAdmin(Request $request)
{
return $this->Title_complaintInterface->deleteFromTitle_complaintByAdmin($request);
}



}
