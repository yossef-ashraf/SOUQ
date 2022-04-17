<?php

namespace App\Http\Interfaces;

interface OrderInterface
{
    public function allOrder();

    public function chekout($request);

    public function DoneaddOrder($request);

}
