<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\AuthInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class Authcontroller extends Controller
{

public $authInterface;

public function __construct(AuthInterface $authInterface)
{
$this->authInterface = $authInterface;
}


public function login(Request $request )
{
return $this->authInterface->login($request);
}


public function register( Request $request)
{
return $this->authInterface->register($request);
}


public function auth()
{
return $this->authInterface->auth();
}


}
