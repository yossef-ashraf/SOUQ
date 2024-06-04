<?php

namespace App\Http\Controllers\Web;

use App\Models\Cart;
use App\Rules\Cart\SoldOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Rules\Cart\CheckCartCountFirstTime;
use App\Rules\Cart\CheckCartHasSameProduct;
use App\Rules\Cart\CheckCartCountWhenUpdate;

class CartController extends Controller
{
    public function show()
    {
     $Cart= Cart::where('user_id',auth()->user()->id)->with(['product','product_size','product_detail'])->get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Cart);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:carts,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Cart= Cart::where([['user_id',auth()->user()->id],['id',$request->id]])->with(['product','product_size','product_detail'])->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Cart);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'=> 'required|exists:products,id',
            'product_detail_id'=> 'required|exists:product_details,id',
            'product_size_id'=> 'required|exists:product_sizes,id',
            'count'=> ['required','numeric', new CheckCartCountFirstTime($request->product_size_id) ,new CheckCartHasSameProduct($request->product_id)],
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Cart = 0 ;
        DB::transaction(function () use ($request,&$Cart) {
            $Cart = Cart::create([
                'product_id' => $request->product_id,
                'product_detail_id' => $request->product_detail_id,
                'product_size_id' => $request->product_size_id,
                'count' => $request->count,
                'soldout' => false,
                'user_id' => auth()->user()->id,
            ]);
        });
        return $this->apiResponse(200,__('lang.Successfully'),null,$Cart);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'   => 'required|exists:carts,id',
            'count'=> ['required','numeric', new CheckCartCountWhenUpdate($request->product_size_id) ,new SoldOut()],
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Cart = Cart::where([['user_id', auth()->User()->id],['id',$request->id]])->first();
                $Cart->update([
                    'count' => $request->count,
                ]);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Cart);
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:carts,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Cart = Cart::where([['user_id', auth()->User()->id],['id',$request->id]])->first();
        $Cart->delete();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Cart);

    }
}
