<?php

namespace App\Http\Interfaces;

interface OrderInterface
{
    public function allOrderForAdmin();

    public function allOrderForUser();

    public function OrdersDone();

    public function OrderCheckout();

    public function addOrder();

    public function chekout();

    public function addToOrder($request);

    public function DoneaddOrder($request);

    public function deleteFromOrder($request);

}
