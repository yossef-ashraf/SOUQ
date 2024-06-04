<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserWallet;
use App\Models\OrderReturn;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use App\Models\WalletHistory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Web\WalletController;

class OrderController extends Controller
{
    public function show()
    {
     $Order= Order::with(['user','address','shipping','order_items.product'])->get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Order);
    }
    public function showAll()
    {
        $Order=  Order::withTrashed()->with(['user','address','shipping','order_items.product'])->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Order);
    }
    public function showTrashed()
    {
        $Order= Order::onlyTrashed()->with(['user','address','shipping'])->get();
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
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Order = Order::withTrashed()->with(['user','address','shipping','order_items','order_items.product_detail.product','order_items.product_detail','order_items.product_size'])->findOrFail($request->id);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Order);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'   => 'required|exists:orders,id',
            'status'   => 'required',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Order = Order::findOrFail($request->id);
        // PaymobPayment - payment_type
        DB::transaction(function () use(&$Order , $request){
        if (($Order->status == 'Returned' || $Order->status == 'Cancelled') && ($request->status != 'Cancelled' || $request->status != 'Returned')) {
            if ($Order->status == 'Returned' &&  $request->status != 'Returned') {
            OrderReturn::create([
                'order_id' => $request->id,
                'reason' => "Admin Returned",
                'return' => 0 ,
                'return_price' => $Order->total_price,
            ]);
        }
            $items = OrderItem::where('order_id', $Order->id)->get();
            foreach($items as $item)
            {
                $ProductSize = ProductSize::withTrashed()->where('id', $item->product_size_id)->first();
                $ProductSize->update([
                    'quantity'=> $ProductSize->quantity + $item->count,
                    'order_return'=> $ProductSize->order_return + 1
                ]);
            }
            if (($Order->payment_type == 'PaymobPayment' || $Order->payment_type == 'WalletPayment')&& (($Order->status == 'Returned' || $Order->status == 'Cancelled') && ($request->status != 'Cancelled' || $request->status != 'Returned'))) {
                $this->wallet($Order->total_price,$Order->user_id);

            }

        }
        $Order->update([
            'status' => $request->status,
        ]);
        if ($request->driver_by) {
            $Order->update([
                'driver_by' => $request->driver_by,
            ]);
        }
        });
        return $this->apiResponse(200,__('lang.Successfully'));

    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:orders,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Order = Order::findOrFail($request->id);
        $Order->delete();
        return $this->apiResponse(200,__('lang.Successfully'));

    }
    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:orders,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Order = Order::onlyTrashed()->findOrFail($request->id);
        $Order->restore();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Order);
    }
    public function forceDelete(Request $request)
    {
        // dd('ff');
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:orders,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Order = Order::withTrashed()->findOrFail($request->id);
        $Order->forceDelete();
        return $this->apiResponse(200,__('lang.Successfully'));
    }
}
