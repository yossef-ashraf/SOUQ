<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Interfaces\CartInterface;

class Cartcontroller extends Controller
{

public $cartInterface;
public function __construct(CartInterface $cartInterface)
{
$this->cartInterface = $cartInterface;
}


public function userCart()
{
return $this->cartInterface->userCart();
}

public function addToCart(Request $request)
{
return $this->cartInterface->addToCart($request);
}

public function UpdateCart(Request $request)
{
return $this->cartInterface->UpdateCart($request);
}

public function deleteFromCart(Request $request)
{
return $this->cartInterface->deleteFromCart($request);
}



}
