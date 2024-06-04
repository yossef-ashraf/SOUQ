<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Nafezly\Payments\Classes\PaymobPayment;
use Nafezly\Payments\Factories\PaymentFactory ;

trait PaymentTrait{

public function PaymobPayment($id,$first_name,$last_name,$email,$phone,$amount){

    $payment = new PaymobPayment();
    return $payment->pay(
        $amount ,
        $id ,
        $first_name ,
        $last_name ,
        $email ,
        $phone ,
        'web'
    );
    // return $payment;
}

public function PaymobPaymentVerify($request)
{
    $payment = new PaymobPayment();
    $payment = $payment->verify($request);
    if ($payment['success'] == 'success' ) {
        return \Redirect::away('');
    }else {
        return \Redirect::away('');
    }
}

public function PaymobPaymentRefund($transaction_id,$amount)
{
    $payment = new PaymobPayment();
    $payment = $payment->refund($transaction_id,$amount);
    if ($payment['success'] == 'success') {
        return \Redirect::away('');
    } else {
        return \Redirect::away('');
    }

}

}





