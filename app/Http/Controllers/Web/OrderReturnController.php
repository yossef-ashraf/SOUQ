<?php

namespace App\Http\Controllers\Web;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Shipping;
use App\Models\UserWallet;
use App\Models\OrderReturn;
use App\Models\ProductSize;
use App\Rules\Order\SoldOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrderReturnController extends Controller
{
    public function show()
    {
     $OrderReturn= OrderReturn::where('user_id',auth()->user()->id)->with(['order','order.product_detail.product','order.product_detail','order.product_size'])->get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$OrderReturn);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:order_returns,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $OrderReturn= OrderReturn::where('user_id',auth()->user()->id)
        ->with(['order.address','order.shipping','order',
        'order.order_items.product_detail.product',
        'order.order_items.product_detail',
        'order.order_items.product_size'
        ])
        ->first();
        return $this->apiResponse(200,__('lang.Successfully'),null,$OrderReturn);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id'=> 'required|exists:orders,id',
            'reason' =>'required',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }

        DB::transaction(function () use ($request) {
            // order_items.product_size
            $Order = Order::where([['user_id', auth()->User()->id],['order_id', $request->order_id]])
            ->with('order_items')
            ->first();
            if (($Order->payment_type == 'PaymobPayment' || $Order->payment_type == 'WalletPayment' )&& ($Order->status == 'Confirmed'|| $Order->status == 'Pending')) {
                $this->wallet( $Order->total_price ,auth()->user()->id);
            }
            $OrderReturn = OrderReturn::create([
                'order_id' => $request->order_id,
                'reason' => $request->reason,
                'return' => 0 ,
                'return_price' => $Order->total_price,
            ]);
            foreach($Order->order_items as $cartitem){
                $ProductSize = ProductSize::where([ ['id', $cartitem->product_size_id] ])->first();
                if ($ProductSize) {
                    $ProductSize->update([
                        'quantity'=> $ProductSize->quantity - $cartitem->count,
                        'OrderReturn'=> $ProductSize->OrderReturn + 1
                    ]);
            }}
        });
        return $this->apiResponse(200,__('lang.Successfully'));
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:order_returns,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $OrderReturn = OrderReturn::where([['user_id', auth()->User()->id],['id',$request->id]])->first();
        $OrderReturn->delete();
        return $this->apiResponse(200,__('lang.Successfully'));

    }

}
