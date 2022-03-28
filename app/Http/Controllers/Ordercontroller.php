<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Interfaces\OrderInterface;

class Ordercontroller extends Controller
{
public $OrderInterface;

public function __construct(OrderInterface $OrderInterface)
{
$this->OrderInterface = $OrderInterface;
}

public function allOrderForAdmin(){
return $this->OrderInterface->allOrderForAdmin();
}


public function allOrderForUser(){
return $this->OrderInterface->allOrderForUser();
}


public function OrdersDone(){
return $this->OrderInterface->OrdersDone();
}


public function OrderCheckout(){
return $this->OrderInterface->OrderCheckout();
}


public function addOrder(){
return $this->OrderInterface->addOrder();
}


public function chekout(){
return $this->OrderInterface->chekout();
}

public function addToOrder(Request $request){
return $this->OrderInterface->addToOrder($request);
}

public function DoneaddOrder(Request $request){
return $this->OrderInterface->DoneaddOrder($request);
}

public function deleteFromOrder(Request $request){
return $this->OrderInterface->deleteFromOrder($request);
}

}
