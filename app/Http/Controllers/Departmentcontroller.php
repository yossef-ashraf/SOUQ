<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Interfaces\DepartmentInterface;


class Departmentcontroller extends Controller
{
public $DepartmentInterface;

public function __construct(DepartmentInterface $DepartmentInterface)
{
$this->DepartmentInterface = $DepartmentInterface;
}

public function departmentForAdmin(){
return $this->DepartmentInterface->departmentForAdmin();
}


public function departments(){
return $this->DepartmentInterface->departments();
}


public function deletedepartment(Request $request){
return $this->DepartmentInterface->deletedepartment($request);
}

public function adddepartment(Request $request){
return $this->DepartmentInterface->adddepartment($request);
}


public function updatedepartmentByAdmin(Request $request){
return $this->DepartmentInterface->updatedepartmentByAdmin($request);
}
}
