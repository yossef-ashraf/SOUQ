<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Interfaces\CategoryInterface;


class Categorycontroller extends Controller
{
public $CategoryInterface;

public function __construct(CategoryInterface $CategoryInterface)
{
$this->CategoryInterface = $CategoryInterface;
}

public function categoryForAdmin(){
return $this->CategoryInterface->categoryForAdmin();
}


public function categorys(Request $request){
return $this->CategoryInterface->categorys($request);
}


public function deletecategory(Request $request){
return $this->CategoryInterface->deletecategory($request);
}

public function addcategory(Request $request){
return $this->CategoryInterface->addcategory($request);
}


public function updatecategoryByAdmin(Request $request){
return $this->CategoryInterface->updatecategoryByAdmin($request);
}
}
