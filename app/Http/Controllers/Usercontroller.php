<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Interfaces\UserInterface;


class Usercontroller extends Controller
{
public $UserInterface;

public function __construct(UserInterface $UserInterface)
{
$this->UserInterface = $UserInterface;
}

public function users(){
    return $this->UserInterface->users();
}

public function deleteuser(Request $request){
    return $this->UserInterface->deleteuser($request);
}

public function updateuserByAdmin(Request $request){
    return $this->UserInterface->updateuserByAdmin($request);
}

public function updateuser(Request $request){
    return $this->UserInterface->updateuser($request);
}


}
