<?php

namespace App\Http\Controllers\Web;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Shipping;
use App\Models\OrderItem;
use App\Models\UserWallet;
use App\Models\ProductSize;
use App\Rules\Order\SoldOut;
use Illuminate\Http\Request;
use App\Rules\Promo\PromoCode;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class OrderController extends Controller
{
    public function show()
    {
     $Order= Order::where('user_id',auth()->user()->id)->with(['order_items','order_items.product_detail.product','order_items.product_detail','order_items.product_size'])->get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Order);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:orders,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Order= Order::where('user_id',auth()->user()->id)->with(['address','shipping','order_items','order_items.product_detail.product','order_items.product_detail','order_items.product_size'])->first();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Order);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipping_id'=>  ['required','exists:shippings,id', new SoldOut],
            'address_id'=> 'required|exists:addresses,id',
            'payment_type'=> 'required',
            'phone'=> 'required',
            'promo' =>['exists:promos,promo'],
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // حساب المبلغ الكلي و التاكد من ان هنالك منتجات
        $cartitems=Cart::where('user_id', auth()->user()->id)->with('product_size')->get();
        if (!count($cartitems) == 0 ){
            $discount = 0 ;
            $sub_total = 0 ;
        $total_price_order = $cartitems->sum(function($item) use(&$sub_total , &$discount){
            $sub_total_fun = ($item->count * $item->product_size->price);
            $discount_fun =($item->count * $this->discount($item->product_size->price,$item->product_id)) ;
            $discount =  $discount + $discount_fun ;
            $sub_total =  $sub_total + $sub_total_fun ;
            return  $sub_total_fun - $discount_fun;
        });


        $Shipping= Shipping::where('id', $request->shipping_id)->select('id','price')->first();
        if ($request->promo != null) {
            $total_price_order = $this->promo($total_price_order,$request->promo) + $Shipping->price;
        }else{
            $total_price_order = $total_price_order + $Shipping->price;
        }

        }else {
        return $this->apiResponse(400,__('lang.validationError'),'User Carts');
        }
        $status = 'UnConfirmed';
        // التاكد من ان المبلغ المدفوع يكفي
        if ($request->payment_type == 'WalletPayment') {
            $UserWallet= UserWallet::where('user_id', auth()->user()->id)->first();
            if ($total_price_order > $UserWallet->wallet) {
                return $this->apiResponse(400,__('lang.validationError'),'UserWallet');
            }
            $status = 'Confirmed';
            $this->wallet(($total_price_order * -1),auth()->user()->id);
        }elseif($request->payment_type == 'PaymobPayment'){
            $status = 'Confirmed';
        }
        DB::transaction(function () use ($request , $status, $cartitems , $sub_total , $discount , $total_price_order ) {

            $Order = Order::create([
                'user_id' => auth()->user()->id,
                'shipping_id' => $request->shipping_id,
                'address_id' => $request->address_id,
                'phone' => $request->phone,
                'discount' => $discount,
                'sub_total' => $sub_total,
                'total_price' => $total_price_order,
                'payment_type' => $request->payment_type,
                'promo' => $request->promo ?? null,
                'status' => $status,
            ]);

            foreach($cartitems as $cartitem)
            {
                $sub_total_item = ($cartitem->count * $cartitem->product_size->price);
                $discount_item =($cartitem->count * $this->discount($cartitem->product_size->price,$cartitem->product_id)) ;
                $total_price_item = $sub_total_item -  $discount_item;
                OrderItem::create([
                    'order_id' => $Order->id,
                    'product_size_id' => $cartitem->product_size_id ,
                    'product_detail_id' => $cartitem->product_detail_id ,

                    'count' => $cartitem->count,
                    'price' => $cartitem->product_size->price ,
                    'discount' => $discount_item,
                    'sub_total' => $sub_total_item,
                    'total_price' => $total_price_item,
                ]);
                $ProductSize = ProductSize::where([ ['id', $cartitem->product_size_id] ])->first();
                $ProductSize->update([
                    'quantity'=> $ProductSize->quantity - $cartitem->count,
                    'order'=> $ProductSize->order + 1
                ]);
                $cartitem->delete();
            }

            // if ($payment_type == 'PaymobPayment') {
            //     $PaymobPayment = $this->PaymobPayment(
            //         auth()->user()->id,
            //         auth()->user()->name,
            //         'ss ',
            //         auth()->user()->email,
            //         $request->phone,
            //         $total_price_item,
            //     );
            // }

        });

        return $this->apiResponse(200,__('lang.Successfully'));

    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'   => 'required|exists:orders,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Order = Order::where([['user_id', auth()->User()->id],['id',$request->id]])->first();
        // PaymobPayment - payment_type
        if ($Order->status == 'UnConfirmed' || $Order->status == 'Confirmed'|| $Order->status == 'Pending') {
        DB::transaction(function () use(&$Order , $request){

            $items = OrderItem::where('order_id', $Order->id)->get();
            foreach($items as $item)
            {
                $ProductSize = ProductSize::withTrashed()->where('id', $item->product_size_id)->first();
                $ProductSize->update([
                    'quantity'=> $ProductSize->quantity + $item->count,
                    'order_return'=> $ProductSize->order_return + 1
                ]);
            }
            if (($Order->payment_type == 'PaymobPayment' || $Order->payment_type == 'WalletPayment' )&& ($Order->status == 'Confirmed'|| $Order->status == 'Pending')) {
                $this->wallet( $Order->total_price ,auth()->user()->id);
            }
            $Order->update([
                'status' => 'Cancelled',
            ]);
        });
        return $this->apiResponse(200,__('lang.Successfully'),null,$Order);
        }
        return $this->apiResponse(400, __('lang.validationError'));
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:orders,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Order = Order::where([['user_id', auth()->User()->id],['id',$request->id]])->first();
        $Order->delete();
        return $this->apiResponse(200,__('lang.Successfully'));

    }
}
