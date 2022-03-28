<?php
namespace App\Http\Repositories;

use App\Models\cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Rules\StockValidation;
use App\Rules\StockValidationup;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Interfaces\CartInterface;
use Illuminate\Support\Facades\Validator;


class CartRepository implements CartInterface
{
use ApiResponseTrait;



public function userCart()
{
$carts = Cart::where('user_id', Auth::user()->id)->with('products')->get();
return $this->apiResponse(200, 'user cart', null, $carts);
}


public function addToCart($request)
{
$validations = Validator::make($request->all(),[
'product_id' => 'required|exists:products,id',
'count' => ['required', new StockValidation($request->product_id)]
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

$cart= Cart::where([ ['user_id', Auth::user()->id], ['product_id', $request->product_id] ])->first();

if($cart)
{

$cart->update([
'count' => ($cart->count + $request->count)
]);

}else
{
Cart::create([
'user_id' => Auth::user()->id,
'product_id' => $request->product_id,
'count' => $request->count
]);
}

return $this->apiResponse(200, 'added to cart');
}


public function deleteFromCart($request)
{

$validations = Validator::make($request->all(),[
'id' => 'required|exists:carts,id'
]);
// dd($request->id);
if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

Cart::where('id', $request->id)->delete();

return $this->apiResponse(200, 'delete cart is done');
}


public function UpdateCart($request)
{
$validations = Validator::make($request->all(),[
'product_id' => 'required|exists:products,id',
'count' => ['required', new StockValidationup($request->product_id)]
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

$cart= Cart::where([ ['user_id', Auth::user()->id], ['product_id', $request->product_id] ])->first();
$cart->update([
'count' => ($cart->count + $request->count)
]);
return $this->apiResponse(200, 'Update Cart');
}

}







