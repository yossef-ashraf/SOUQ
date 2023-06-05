<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Rules\Count;
use App\Rules\Stoke;
use App\Rules\UpStoke;
use App\Rules\HaveProduct;
use Illuminate\Http\Request;
use App\Rules\IfCartHasError;
use App\Http\Resources\CartResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
use ApiResponseTrait;

public function Cart()
{
        $carts = Cart::where('user_id', Auth::user()->id)->with(['products'])->get();
        return $this->apiResponse(200, 'user cart', null, CartResource::collection($carts));
}

public function AddCart(Request $request)
{

    $validations = Validator::make($request->all(), [
        'product_id' => 'required|exists:Products,id',
        'count' => ['required',new Stoke($request->product_id) , new HaveProduct($request->product_id)]
    ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, 'validation error', $validations->errors());
        }

        Cart::create([
            'user_id' => Auth::user()->id,
            'product_id' => $request->product_id,
            'count' => $request->count
        ]);
        $carts = Cart::where('user_id', Auth::user()->id)->with(['products'])->get();
        return $this->apiResponse(200, 'added to cart',CartResource::collection($carts));
}

public function DeleteCart(Request $request)
{
        $validations = Validator::make($request->all(), [
        'id' => 'required|exists:Carts,id'
        ]);
        if ($validations->fails()) {
        return $this->apiResponse(400, 'validation error', $validations->errors());
        }
        Cart::where('id', $request->id)->delete();
        $carts = Cart::where('user_id', Auth::user()->id)->with(['products'])->get();
        return $this->apiResponse(200, 'delete cart is done',CartResource::collection($carts));
}

public function UpdateCart(Request $request)
{
    $validations = Validator::make($request->all(), [
            'id' => 'required|exists:Carts,id',
            'count' => ['required' , new UpStoke( $request->id ) , new Count( $request->id ) ,new IfCartHasError( $request->id)]
    ]);

            if ($validations->fails()) {
                return $this->apiResponse(400, 'validation error', $validations->errors());
            }

    $cart= Cart::where([ ['id', $request->id],['user_id', Auth::user()->id]])->first();
        $cart->update([
            'count' => ($cart->count + $request->count)
    ]);

            $carts = Cart::where('user_id', Auth::user()->id)->with(['products'])->get();
            return $this->apiResponse(200, 'Update Cart',CartResource::collection($carts));
}



}
