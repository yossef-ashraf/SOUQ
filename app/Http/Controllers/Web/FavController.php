<?php

namespace App\Http\Controllers\Web;

use App\Models\Fav;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FavController extends Controller
{
    public function show()
    {
     $Fav= Fav::where('user_id',auth()->user()->id)->with('product')->get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Fav);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:favs,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Fav= Fav::where([['user_id',auth()->user()->id],['id',$request->id]])->with('product')->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Fav);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'=> 'required|exists:products,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Fav = Fav::create([
            'product_id'=>$request->product_id,
            'user_id'=>auth()->user()->id,
        ]);

        $product = Product::withTrashed()->findOrFail($request->product_id);

        $product->update([
            'like_num' => ($product->like_num + 1),
        ]);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Fav);
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:favs,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Fav = Fav::where([['user_id', auth()->User()->id],['id',$request->id]])->first();
        $product = Product::withTrashed()->findOrFail($Fav->product_id);

        $product->update([
            'like_num' => ($product->like_num - 1),
        ]);

        $Fav->delete();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Fav);

    }

}
