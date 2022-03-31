<?php
namespace App\Http\Repositories;

use App\Rules\email;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Rules\StockValidationOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Interfaces\OrderInterface;
use Illuminate\Support\Facades\Validator;


class OrderRepository implements OrderInterface
{
use ApiResponseTrait;

public function allOrderForUser()
{
$arr=Order::where('user_id', auth()->user()->id )->with('order_item')->get();
return $this->apiResponse(200,"Orders",null,$arr);
}


public function OrdersDone()
{
$arr=Order::where([['user_id', auth()->user()->id ],['status_order',true]])->with('order_item')->get();
return $this->apiResponse(200,"Orders Done",null,$arr);
}

public function OrderCheckout()
{
$arr=Order::where([['user_id', auth()->user()->id ],['status-checkout',true]])->with('order_item')->get();
return $this->apiResponse(200,"Orders Checkout",null,$arr);
}

public function addOrder()
{
$Order=Order::where([['user_id', auth()->user()->id ],['status-checkout' , false]])->first();
if ($Order)
{
return $this->apiResponse(200, 'you have order');
}
Order::create([
'user_id' => Auth::user()->id,
'status_order'  => 0 ,
'status-checkout'  => 0,
'total_price' => 0
]);
return $this->apiResponse(200, 'added order');

}


public function addToOrder($request)
{
$validations = Validator::make($request->all(),[
'product_id' => 'required|exists:products,id',
'order_id' => 'required|exists:orders,id',
'count' => ['required', new StockValidationOrder($request->product_id , $request->order_id)]
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}
// end of validations

$product = Product::where([ ['id', $request->product_id], ['status', true] ])->first();
$Order_item=Order_item::where([['product_id', $request->product_id ],['order_id' , $request->order_id]])->with('products')->first();
if ($Order_item)
{
$price=$Order_item->products->price;
$discount=$Order_item->products->discount;
$unit_pricre=($price-($price * ($discount / 100 )));
$count=$request->count + $Order_item->count ;
if ($count >= 0) {
$Order_item->update([
'count' => $count ,
'total_price' => $unit_pricre * $count,
'updated_at	'=> time()
]);
}
}else
{
Order_item::create([
'order_id' => $request->order_id,
'product_id' => $request->product_id,
'count' =>$request->count,
'total_price' =>(($product->price-($product->price*($product->discount / 100 )))*$request->count)
]);
}
// end of add or update Order_item
return $this->apiResponse(200, 'add to order');
}




public function chekout()
{
$total_price=0;
$mess="  ";
$Order=Order::where([['user_id', auth()->user()->id ],['status-checkout' , false]])->with('order_item')->first();
if ($Order)
{
foreach($Order->order_item as $Or)
{
foreach ($Order->order_item as $pro) {
$product = Product::where([ ['id', $pro->product_id], ['status', true] ])->first();
if ($product->stock >= $Or->count)
{
$product->update([
'stock'=>( $product->stock - $Or->count ),
'updated_at	'=> time()
]);
}else{
$mess .=" product ".$product->name." not has ".$Or->count;
}

}
$total_price =$total_price + $Or->total_price;
};
$Order->update([
'user_id' => Auth::user()->id,
'status-checkout'  => 1,
'total_price' => $total_price,
'updated_at	'=> time()
]);
return $this->apiResponse(200, 'chekout order');
}
return $this->apiResponse(400, 'you have not orders');
}


////////////////////////////////////////////////////////////////
// admin



public function allOrderForAdmin()
{
if (auth()->user()->auth == 'admin')
{
$arr=Order::with('products')->get();
return $this->apiResponse(200,"Orders",null,$arr);
}
}

public function DoneaddOrder($request)
{
if (auth()->user()->auth == 'admin')
{
$validations = Validator::make($request->all(),[
'id' => 'required|exists:orders,id'
]);
if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}
$Order=Order::where('id', $request->id)->first();
$Order->update([
'status_order'  => 1,
]);
return $this->apiResponse(200, 'order done');
}

return $this->apiResponse(400, ' order exists' );
}


public function deleteFromOrder($request)
{
if (auth()->user()->auth == 'admin')
{
$validations = Validator::make($request->all(),[
'id' => 'required|exists:orders,id'
]);
// dd($request->id);
if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

Order::where('id', $request->id)->delete();

return $this->apiResponse(200, 'delete Order is done');
}
return $this->apiResponse(400, ' order exists' );
}

}
