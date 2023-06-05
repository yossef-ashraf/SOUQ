<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Discount;
use App\Rules\Discounts;
use App\Models\OrderItem;
use App\Rules\StokeOrder;
use App\Models\Product;
use App\Rules\CheckSoldOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
use ApiResponseTrait;

public function Orders()
{
    $Order=Order::where('user_id', auth()->user()->id ) ->whereNot('status_order' , "Cancelled")->with('order_item')->get();
    return $this->apiResponse(200,"Orders",null,$Order);
}
public function DeleteOrderUser(Request $request)
{
    $validations = Validator::make($request->all(), [
        'id' => 'required|exists:Orders,id'
        ]);
        if ($validations->fails()) {
        return $this->apiResponse(400, 'validation error', $validations->errors());
        }
        $Order=Order::where([['id', $request->id],['user_id' , Auth::user()->id],['status_order' , "Pending"]])->first() ;
        if ($Order) {
            $Order->update([
                'status_order' => 'Cancelled',
                ]);
            return $this->apiResponse(200, 'delete Order is done');
        }
        return $this->apiResponse(400, 'delete Order is not done');
}

public function DiscountOrderUser(Request $request)
{
    $validations = Validator::make($request->all(), [
        // 'id' => 'required|exists:Orders,id'
        'discount_code'=>['required',new Discounts],
        ]);
        if ($validations->fails()) {
        return $this->apiResponse(400, 'validation error', $validations->errors());
        }

    return $this->apiResponse(200, 'discount is done');

}

public function CheckOut(Request $request)
{
    //validation
        $validations = Validator::make($request->all(),[
        'order'=>['required', new StokeOrder , new CheckSoldOut],
        'discount_code'=>[new Discounts],
        'streetAddress' =>  'required',
        'shipping_id' => 'required|exists:Shippings,id',
        'address' => 'required|min:3',
        'specialMark' => 'required|min:3',
        'paymentMethod' => 'required|min:3',
        ]);
        if($validations->fails())
        {
        return $this->apiResponse(400, 'validation error', $validations->errors());
        }

        try {
    // all carts
        $cartitems=Cart::where([['user_id', auth()->user()->id]])->with(['products'])->get();
    //sum price order
        $total_price_order=0;
        $total_price_item=0;

        if (!count($cartitems) == 0 ){
        $total_price_order = $cartitems->sum(function($item)
        {
        return $item->count * ($item->products->price - ($item->products->price * ($item->products->discount / 100 )));
        });

        if ($request->discount_code) {
            $code = Discount::where('code', $request->input('discount_code'))->first();
                    // Calculate the discount amount
                    $discount = $code->amount;
                    // Apply the discount to the purchase total
                    $total_price_order = $total_price_order - ($total_price_order*$discount);
        }



    //DB transaction
    DB::transaction(function()use($total_price_order,$total_price_item,$cartitems,$request) {
    //create order
        $order = Order::create([
        'user_id' => Auth::user()->id,
        'status_order'  => "Pending",
        'total_price' => $total_price_order,
        'streetAddress' => $request->streetAddress,
        'shipping_id' => $request->shipping_id,
        'address' => $request->address,
        'discountcode' => $request->discount_code,
        'specialMark' => $request->specialMark,
        'paymentMethod' => $request->paymentMethod,

        ]);
    //create order items
        foreach($cartitems as $cartitem)
        {
    //sum price order item
        $total_price_item = $cartitem->count * ($cartitem->products->price - ($cartitem->products->price * ($cartitem->products->discount / 100 )));
    //create order item
        OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $cartitem->products->id ,
        'count' => $cartitem->count,
        'discount' => $cartitem->products->discount ,
        'price' => $total_price_item
        ]);
    //delete cart item update address user cheang products quantity

            $Product = Product::where([ ['id', $cartitem->products->id] ])->first();
            // dd($Product->quantity - $cartitem->count);
            $Product->update([
                'quantity'=> $Product->quantity - $cartitem->count
            ]);

            $cartitem->delete();
         }
        });
        return $this->apiResponse(200, 'chekout order');
        }

    // else if count of cartitems = 0
        return $this->apiResponse(400, 'you have no orders');
        }
    //if catch error
        catch (Exception $th) {
        return $this->apiResponse(400, 'catch error', $th->getMessage() );
        }

}


//////////////////////
// admin
public function AdminOrder()
{
    $Order=Order::with('order_item')->get();
    return $this->apiResponse(200,"Orders",null,$Order);
}
public function OrderState(Request $request)
{
    $validations = Validator::make($request->all(),[
        'id' => 'required|exists:Orders,id',
        'status' => 'required',
        ]);

        if($validations->fails())
        {
        return $this->apiResponse(400, 'validation error', $validations->errors());
        }

        $Order=Order::where('id', $request->id )->first();
        $Order->update([
            'status_order' => $request->status,
            ]);
            $Order=Order::with('order_item')->get();
        return $this->apiResponse(200,"Done",$Order);

}
public function DeleteOrder(Request $request)
{
        $validations = Validator::make($request->all(), [
        'id' => 'required|exists:Orders,id'
        ]);
        if ($validations->fails()) {
        return $this->apiResponse(400, 'validation error', $validations->errors());
        }
        Order::where('id', $request->id)->delete();
        $Order=Order::with('order_item')->get();
        return $this->apiResponse(200, 'delete Order is done',$Order);
}

}
