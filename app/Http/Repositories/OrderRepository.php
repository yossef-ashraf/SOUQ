<?php
namespace App\Http\Repositories;

use App\Models\cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Order_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rules\StockValidationOrder;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Interfaces\OrderInterface;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\Timer\Exception;


class OrderRepository implements OrderInterface
{
    use ApiResponseTrait;


    public function chekout($request)
    {
        $validations = Validator::make($request->all(),[
            'order' => new StockValidationOrder
        ]);

        if($validations->fails())
        {
            return $this->apiResponse(400, 'validation error', $validations->errors());
        }

        $total_price_order=0;
        $total_price_item=0;

        $cartitems=cart::where('user_id', auth()->user()->id)->with('products')->get();
// dd(count($cartitems) ? 'ture' : 'false');
        try {
            if (!count($cartitems) == 0 ){
# code...
                $total_price_order=$cartitems->sum(function($item)
                {
                    return $item->count * ($item->products->price - ($item->products->price * ($item->products->discount / 100 )));
                });


// dd($total_price_order);
                DB::transaction(function()use($total_price_order,$total_price_item,$cartitems) {
                    $order = Order::create([
                        'user_id' => Auth::user()->id,
                        'status_order'  => 0,
                        'total_price' => $total_price_order
                    ]);
                    foreach($cartitems as $cartitem)
                    {
                        $total_price_item = $cartitem->count * ($cartitem->products->price - ($cartitem->products->price * ($cartitem->products->discount / 100 )));

                        Order_item::create([
                            'order_id' => $order->id,
                            'product_id' => $cartitem->products->id ,
                            'count' => $cartitem->count,
                            'total_price' => $total_price_item
                        ]);

                        $product = Product::where([ ['id', $cartitem->products->id], ['status', true] ])->first();
                        $product->update([
                            'stock'=>( $cartitem->products->stock - $cartitem->count )
                        ]);

                        $cartitem->delete();

                    }
                });
                return $this->apiResponse(200, 'chekout order');
            }
            return $this->apiResponse(400, 'you have not orders');
        } catch (\Exception $th) {
            return $this->apiResponse(400, 'catch error', $th->getMessage() );
        }



    }

/////
    public function allOrder()
    {
        if (auth()->user()->auth == 'admin')
        {
            $arr=Order::with('order_item')->get();
            return $this->apiResponse(200,"Orders",null,$arr);
        }
        $arr=Order::where('user_id', auth()->user()->id )->with('order_item')->get();
        return $this->apiResponse(200,"Orders",null,$arr);
    }

//////
    public function OrdersDone($request)
    {
//        dd($request->id);
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


}


