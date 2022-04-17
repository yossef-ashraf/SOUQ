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

    public function allOrder(){
        return $this->OrderInterface->allOrder();
    }

    public function chekout(Request $request){
        return $this->OrderInterface->chekout($request);
    }

    public function DoneaddOrder(Request $request){
        return $this->OrderInterface->DoneaddOrder($request);
    }

}
