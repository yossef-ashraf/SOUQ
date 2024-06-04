<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReturn;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrderReturnController extends Controller
{
    public function show()
    {
     $Order= OrderReturn::with('order')->get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Order);
    }
    public function showAll()
    {
        $Order=  OrderReturn::withTrashed()->with('order')->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Order);
    }
    public function showTrashed()
    {
        $Order= OrderReturn::onlyTrashed()->with('order')->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Order);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:order_returns,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Order = OrderReturn::withTrashed()
        ->with(['order.user','order.address','order.shipping','order','order.order_items.product_detail.product','order.order_items.product_detail','order.order_items.product_size'])
        ->findOrFail($request->id);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Order);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id'=> 'required|exists:orders,id',
            'user_id'=> 'required|exists:users,id',
            'reason' =>'required',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }

        DB::transaction(function () use ($request) {
            $Order = Order::where([['user_id', $request->user_id],['order_id', $request->order_id]])
            ->with('order_items')
            ->first();
            if (($Order->payment_type == 'PaymobPayment' || $Order->payment_type == 'WalletPayment' )&& ($Order->status == 'Confirmed'|| $Order->status == 'Pending')) {
                $this->wallet( $Order->total_price ,$request->user_id);
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
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'   => 'required|exists:order_returns,id',
            'return'   => 'required|boolean',
            // 'return_price'   => 'required',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Order = OrderReturn::findOrFail($request->id);
        $Order->update([
            'return' => $request->return,
        ]);

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
        $Order = OrderReturn::findOrFail($request->id);
        $Order->delete();
        return $this->apiResponse(200,__('lang.Successfully'));

    }
    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:order_returns,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Order = OrderReturn::onlyTrashed()->findOrFail($request->id);
        $Order->restore();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Order);
    }
    public function forceDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:order_returns,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Order = OrderReturn::withTrashed()->findOrFail($request->id);
        $Order->forceDelete();
        return $this->apiResponse(200,__('lang.Successfully'));
    }
}
