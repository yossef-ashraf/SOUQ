<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    use ApiResponseTrait, ImageTrait;

public function Shipping()
{
    $Shipping=Shipping::get();
        return $this->apiResponse(200,"Shippings",null, $Shipping);
}

public function AdminShipping()
{
    $Shipping=Shipping::paginate(10);
     return $this->apiResponse(200,"Shippings",null, $Shipping);
}
public function AddShipping(Request $request)
{
    $validations = Validator::make($request->all(),[
        'city' => 'required|string',
        'price' => 'required|numeric',
    ]);

    if($validations->fails())
    {
    return $this->apiResponse(400, 'validation error', $validations->errors());
    }
    Shipping::create([
    'city' => $request->city,
    'price' =>  $request->price,

    ]);
    $Shipping=Shipping::paginate(10);
    return $this->apiResponse(200, 'Shipping Created successfully',$Shipping);

}
//
public function UpdateShipping(Request $request)
{

    $validations = Validator::make($request->all(),[
    'id' => 'required|exists:Shippings,id',
    'city' => 'required|string',
    'price' => 'required|numeric',
    ]);
    if($validations->fails())
    {
    return $this->apiResponse(400, 'validation error', $validations->errors());
    }

    $Shipping= Shipping::where( 'id' , $request->id )->first();
    $Shipping->update([
    'city' => $request->city,
    'price' =>  $request->price,
    'updated_at	'=> time()
    ]);

    $Shipping=Shipping::paginate(10);
    return $this->apiResponse(200, 'update Shipping is done',$Shipping);
}
///
public function DeleteShipping(Request $request)
{
    $validations = Validator::make($request->all(),[
    'id' => 'required|exists:Shippings,id'
    ]);
    if($validations->fails())
    {
    return $this->apiResponse(400, 'validation error', $validations->errors());
    }

    $Shipping= Shipping::where( 'id' , $request->id )->first();
    $Shipping->delete();
    $Shipping=Shipping::paginate(10);

    return $this->apiResponse(200, 'delete Shipping is done',$Shipping);
}
}
