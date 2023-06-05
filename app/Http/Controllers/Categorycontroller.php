<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use ApiResponseTrait;

public function Categories()
{
    $Category=Category::get();
    return $this->apiResponse(200,"Categorys",null,$Category);
}
public function Category(Request $request)
{
    $validations = Validator::make($request->all(),[
        'id' => 'required|exists:Categories,id',
    ]);
    if($validations->fails())
    {
    return $this->apiResponse(400, 'validation error', $validations->errors());
    }
    $Category=Category::where('id',$request->id)->with('Product')->get();

    return $this->apiResponse(200,"categories",null,$Category);
}

public function Search(Request $request)
{
    $validations = Validator::make($request->all(),[
        'querys' => 'required'  ,
        ]);
        if($validations->fails())
        {
        return $this->apiResponse(400, 'validation error', $validations->errors());
        }
        $query=substr(json_encode($request->querys ), 1, -1) ?? 'a b c d';
        $Category = Category::Where('name', 'like', '%' . $query  . '%')->with('Product')->get();
         return $this->apiResponse(200,"Category",null,$Category);
}
// admin
public function AddCategory(Request $request)
{
    $validations = Validator::make($request->all(),[
        'name' => 'required|array',
        'name.*' => 'string',
    ]);

    if($validations->fails())
    {
    return $this->apiResponse(400, 'validation error', $validations->errors());
    }
    Category::create([
    'name' => $request->name,
    ]);
    $Category=Category::get();
    return $this->apiResponse(200, 'account was created' , $Category);

}
//
public function UpdateCategory(Request $request)
{

    $validations = Validator::make($request->all(),[
    'id' => 'required|exists:Categories,id',
    'name' => 'required|array',
    'name.*' => 'string',
    ]);
    if($validations->fails())
    {
    return $this->apiResponse(400, 'validation error', $validations->errors());
    }

    $Category= Category::where( 'id' , $request->id )->first();
    $Category->update([
    'name' => $request->name,
    'updated_at	'=> time()
    ]);
    $Category=Category::get();
    return $this->apiResponse(200, 'update Category is done' ,$Category);
}
///
public function DeleteCategory(Request $request)
{

    $validations = Validator::make($request->all(),[
    'id' => 'required|exists:Categories,id'
    ]);
    if($validations->fails())
    {
    return $this->apiResponse(400, 'validation error', $validations->errors());
    }

    $Category= Category::where( 'id' , $request->id )->first();

    $Products= Product::where( 'category_id' , $request->id )->get();

    foreach ($Products as $Product ) {

        Storage::delete($Product->image);
    }

    $Category->delete();

    $Category=Category::get();

    return $this->apiResponse(200, 'delete Category is done' , $Category);
}




}
