<?php

namespace App\Http\Interfaces;

interface CartInterface
{
    public function userCart();

    public function addToCart($request);

    public function UpdateCart($request);

    public function deleteFromCart($request);


}
