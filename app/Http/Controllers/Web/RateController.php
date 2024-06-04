<?php

namespace App\Http\Controllers\Web;

use App\Models\Rate;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RateController extends Controller
{
    public function show()
    {
     $Rate= Rate::where('user_id',auth()->user()->id)->with('product')->get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Rate);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:rates,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Rate= Rate::where([['user_id',auth()->user()->id],['id',$request->id]])->with('product')->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Rate);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'=> 'required|exists:products,id',
            'rate'=> 'required|numeric',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Rate = 0 ;
        DB::transaction(function () use ($request,&$Rate) {
            $Rate = Rate::create([
                "rate" => $request->rate,
                'product_id' => $request->product_id,
                'user_id' => auth()->user()->id,
            ]);

            $product = Product::withTrashed()->findOrFail($request->product_id);
            $rateSum = Rate::where('product_id', $request->product_id)->sum('rate');
            $rateCount = Rate::where('product_id', $request->product_id)->count();
            $rateAverage = $rateSum / $rateCount;

            $product->update([
                'rate_average' => $rateAverage,
                'rate_num' => $rateCount,
            ]);
        });
        return $this->apiResponse(200,__('lang.Successfully'),null,$Rate);
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:rates,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }

        $Rate = Rate::where([['user_id', auth()->User()->id],['id',$request->id]])->first();
        $Rate->delete();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Rate);

    }

}
