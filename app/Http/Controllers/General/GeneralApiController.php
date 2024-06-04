<?php

namespace App\Http\Controllers\General;

use App\Models\Brand;
use App\Models\Contact;
use App\Models\Product;
use App\Mail\SampleMail;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class GeneralApiController extends Controller
{
    public function showAllCategoryTrashed()
    {
        $categories = Category::withTrashed()->select('id', 'name','offer')->get()->makeHidden(['imgurl']);
        return $this->apiResponse(200, __('lang.Successfully'), null, $categories);
    }

    public function showAllProductTrashed(Request $request)
    {
        $Product = Product::withTrashed()->where('id' , $request->id)->select('id', 'name','discount')->get();
        empty($Product) ?$Product= null : $Product ;
        return $this->apiResponse(200, __('lang.Successfully'), null, $Product);
    }

    public function showAllBrandTrashed()
    {
        $Brand = Brand::withTrashed()->select('id', 'name','offer')->get()->makeHidden(['imgurl']);
        return $this->apiResponse(200, __('lang.Successfully'), null, $Brand);
    }

    public function showAllBrand()
    {
        $Brand = Brand::select('id', 'name','offer')->get()->makeHidden(['imgurl']);
        return $this->apiResponse(200, __('lang.Successfully'), null, $Brand);
    }

    public function showAllCategory()
    {
        $categories = Category::select('id', 'name','offer')->get()->makeHidden(['imgurl']);
        return $this->apiResponse(200, __('lang.Successfully'), null, $categories);
    }

    public function showAllProduct(Request $request)
    {
        $Product = Product::where('id' , $request->id)->select('id', 'name','discount')->get();
        return $this->apiResponse(200, __('lang.Successfully'), null, $Product);
    }

    public function contactStore(Request $request){
        $validator = Validator::make($request->all(), [
            'firstname'=> 'required|max:255',
            'lastname'=> 'required|max:255',
            'email'=> 'required|max:255',
            'message'=> 'required',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Contact = Contact::create([
            'firstname'=>$request->firstname,
            'lastname'=>$request->lastname,
            'email'=>$request->email,
            'message'=>$request->message,
        ]);
        $SampleMail=[
            'firstname'=>$request->firstname,
            'lastname'=>$request->lastname,
            'email'=>$request->email,
            'message'=>$request->message,
        ];
        Mail::to($request->email)->send(new SampleMail($SampleMail));

        return $this->apiResponse(200,__('lang.Successfully'),null,$Contact);
    }

    public function promoCheck(Request $request){
        $validator = Validator::make($request->all(), [
            'promo' =>'required|exists:promos,promo',
            'price'=> 'required',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $promo=$this->promo($request->price,$request->promo);
        return $this->apiResponse(200,__('lang.Successfully'),null,$promo);
    }
}
