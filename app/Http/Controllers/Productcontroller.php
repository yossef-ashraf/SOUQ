<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Interfaces\ProductInterface;


class Productcontroller extends Controller
{
    public $ProductInterface;

    public function __construct(ProductInterface $ProductInterface)
    {
    $this->ProductInterface = $ProductInterface;
    }


    public function products(){
        return $this->ProductInterface->products();
    }


    public function productsForUser(){
        return $this->ProductInterface->productsForUser();
    }


    public function deleteproduct(Request $request){
        return $this->ProductInterface->deleteproduct($request);
    }

    public function addproduct(Request $request){
        return $this->ProductInterface->addproduct($request);
    }


    public function updateproductByAdmin(Request $request){
        return $this->ProductInterface->updateproductByAdmin($request);
    }
}
