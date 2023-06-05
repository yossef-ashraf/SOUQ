<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    use ApiResponseTrait, ImageTrait;

    public function Discount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|exists:Discounts,code',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(400, 'validation error', $validator->errors());
        }
        $Discount = Discount::where("code",$request->code)->first();
        return $this->apiResponse(200,"done",null, $Discount);
    }
    public function AdminDiscount()
    {
        $Discount=Discount::get();
         return $this->apiResponse(200,"Discounts",null, $Discount);
    }
    public function AddDiscount(Request $request)
    {
        $validations = Validator::make($request->all(),[
            'code' => 'required|string|unique:Discounts',
            'amount' => 'required|numeric',
            'expires_at' => 'required|after:now',
        ]);

        if($validations->fails())
        {
        return $this->apiResponse(400, 'validation error', $validations->errors());
        }
        Discount::create([
        'code' => $request->code,
        'amount' =>  $request->amount,
        'expires_at' =>  $request->expires_at,

        ]);
        $Discount=Discount::get();
        return $this->apiResponse(200, 'Discount Created successfully',$Discount);

    }
    //
    public function UpdateDiscount(Request $request)
    {

        $validations = Validator::make($request->all(),[
        'id' => 'required|exists:Discounts,id',
        'code' =>[ 'required','string',Rule::unique('Discounts')->ignore($request->id)],
        'amount' => 'required|numeric',
        'expires_at' => 'required|after:now',
        ]);
        if($validations->fails())
        {
        return $this->apiResponse(400, 'validation error', $validations->errors());
        }

        $Discount= Discount::where( 'id' , $request->id )->first();
        $Discount->update([
        'code' => $request->code,
        'amount' =>  $request->amount,
        'expires_at' =>  $request->expires_at,
        'updated_at	'=> time()
        ]);

        $Discount=Discount::get();
        return $this->apiResponse(200, 'update Discount is done',$Discount);
    }
    ///
    public function DeleteDiscount(Request $request)
    {
        $validations = Validator::make($request->all(),[
        'id' => 'required|exists:Discounts,id'
        ]);
        if($validations->fails())
        {
        return $this->apiResponse(400, 'validation error', $validations->errors());
        }

        $Discount= Discount::where( 'id' , $request->id )->first();
        $Discount->delete();
        $Discount=Discount::get();

        return $this->apiResponse(200, 'delete Discount is done',$Discount);
    }
}
