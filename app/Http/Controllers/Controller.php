<?php

namespace App\Http\Controllers;

use App\Http\Traits\FileTrait;
use App\Http\Traits\OrderTrait;
use App\Http\Traits\PaymentTrait;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests , ApiResponseTrait , FileTrait , PaymentTrait ,OrderTrait;
}
