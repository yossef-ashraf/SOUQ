<?php
namespace App\Http\Repositories;


use App\Rules\email;
use App\Rules\image;
use App\Rules\images;
use App\Models\Product;
use App\Models\category;
use Illuminate\Http\Request;
use App\Models\product_image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Interfaces\ProductInterface;
use Illuminate\Support\Facades\Validator;

class ProductRepository implements ProductInterface
{
use ApiResponseTrait;
/////////////////////////////////////////////////////////////////////
public function products()
{
if (auth()->user()->auth == 'admin' )
{
$arr=Product::with('category','images')->get();
return $this->apiResponse(200,"products",null,$arr);
}
}
//








public function productsForUser($request)
{
$validations = Validator::make($request->all(),[
'category_id' => 'required|exists:categories,id'
]);
if($validations->fails()){
return $this->apiResponse(400, 'validation error', $validations->errors());
}
$arr=Product::where([['category_id', $request->category_id],['status' , true ]])->with('category','images')->get();
return $this->apiResponse(200,"products",null,$arr);
}
/////////////////////////////////////////////////////////////////////






public function addproduct($request)
{

if (auth()->user()->auth == 'admin')
{
$validations = Validator::make($request->all(),[
'name' => 'required|min:3',
'img' => ['required', new image],
'images'=>[ new images],
'desc' => 'required',
'discount' => 'required',
'status' => 'required',
'price' => 'required',
'stock' => 'required',
'category_id' => 'required|exists:categories,id'
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}
try{
DB::transaction(function()use($request){
// // create product
$imagePath = time() . '_product.' . $request->img->extension();
$request->img->move(public_path('images/product'), $imagePath);
$Product= Product::create([
'name' => $request->name,
'desc' => $request->desc,
'discount' => $request->discount,
'status' => $request->status,
'img' => asset('images/product/'.$imagePath),
'price' => $request->price,
'stock' => $request->stock,
'category_id' => $request->category_id
]);
// sart foreach
foreach ($request->images as $img) {
$imagePath = time() . rand() .'_product.' . $img->extension();
$img->move(public_path('images/product'), $imagePath);
# code...
product_image::create([
'product_id' => $Product->id,
'images' => asset('images/product/'.$imagePath),
]);
}
// end foreach

});

return $this->apiResponse(200, 'Product was created');
} catch (\Exception $th) {
return $this->apiResponse(400, 'catch error', $th->getMessage() );
}


}
return $this->apiResponse(400,"you not admin ");
}










public function updateproductByAdmin($request)
{
//dd(auth()->user()->auth);
if (auth()->user()->auth == 'admin' )
{

$validations = Validator::make($request->all(),[
'id' => 'required|exists:products,id',
'name' => 'required|min:3',
'desc' => 'required',
'img' => [ new image],
'images'=>[ new images],
'discount' => 'required',
'status' => 'required',
'price' => 'required',
'stock' => 'required',
'category_id' => 'required|exists:categories,id'
]);
if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

try{
// $request=$request;
// dd($request);
DB::transaction(function()use($request){
$Product= Product::where( 'id' , $request->id )->first();
$Product->update([
'name' => $request->name,
'desc' => $request->desc,
'stock' => $request->stock,
'price' => $request->price,
'discount' => $request->discount,
'status' => $request->status,
'category_id' => $request->category_id,
'updated_at	'=> time()
]);

if ($request->img) {
unlink(public_path('images/product' . explode('/product', $Product->img)[1]));
$imagePath = time() . '_product.' . $request->img->extension();
$request->img->move(public_path('images/product'), $imagePath);
$Product->update([
'img' => asset('images/product/' . $imagePath),
]);

}

if ($request->images) {
# code...
$product_images= product_image::where( 'Product_id' , $Product->id )->get();
if ($product_images) {
foreach ($product_images as $product_image) {
// dd(explode('/product', $product_image->images)[1]);
unlink(public_path('images/product' . explode('/product', $product_image->images)[1]));
$product_image->delete();
}
}

foreach ($request->images as $img) {
$imagesPath = time() . rand() .'_product.' . $img->extension();
$img->move(public_path('images/product'), $imagesPath);
product_image::create([
'product_id' => $Product->id,
'images' => asset('images/product/'.$imagesPath),
]);
}

}


});
return $this->apiResponse(200, 'Product was update');
} catch (\Exception $th) {
return $this->apiResponse(400, 'catch error', $th->getMessage() );
}

}else{

return $this->apiResponse(400, 'you not admin');
}

}











public function deleteproduct($request)
{
if (auth()->user()->auth == 'admin' )
{
$validations = Validator::make($request->all(),[
'id' => 'required|exists:products,id'
]);
// dd($request->id);
if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}
DB::transaction(function()use($request){
$Product= Product::where( 'id' , $request->id )->first();
unlink(public_path('images/product'.explode('/product',$Product->img)[1]));
$Product->delete();

$product_images= product_image::where( 'Product_id' , $Product->id )->get();
if ($product_images) {
foreach ($product_images as $product_image) {
unlink(public_path('images/product' . explode('/product', $product_image->images)[1]));
$product_image->delete();
}
}
});
return $this->apiResponse(200, 'delete Product is done');
}
return $this->apiResponse(400,"you not admin ");
}











}



