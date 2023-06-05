<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ApiResponseTrait;
    use ImageTrait;

    public function Products()
    {
        $Product=Product::get();
        return $this->apiResponse(200, "Products", null, ProductResource::collection($Product));
    }
    public function Product($id)
    {
        try {
            $Product=Product::where('id', $id)->first();
            return $this->apiResponse(200, "Product", null, ProductResource::collection($Product));
        } catch (\Throwable $th) {
            //throw $th;
            return $this->apiResponse(404, "error id id not found");
        }

    }
    public function NewProducts()
    {
        $Product=Product::get()->reverse()->take(10);
        return $this->apiResponse(200, "NewProducts", null, ProductResource::collection($Product));
    }
    public function ProductByCategory(Request $request)
    {
        $validations = Validator::make($request->all(),[
            'category_id' => 'required|exists:Categories,id'
            ]);
            if($validations->fails())
            {
            return $this->apiResponse(400, 'validation error', $validations->errors());
            }


        $Product = Product::
        where('state', 1)
        ->where('category_id', $request->category_id)
        ->get();

        return $this->apiResponse(200, "Products", null, ProductResource::collection($Product));
    }

    public function Search(Request $request)
    {
        $validations = Validator::make($request->all(), [
            'name' => 'required|string',
            'discount' => 'numeric',
            'size' => 'numeric',
            'quantity' => 'numeric',
            'price' => 'numeric',
        ]);

        if($validations->fails()) {
            return $this->apiResponse(400, 'validation error', $validations->errors());
        }

        $name=substr(json_encode($request->name), 1, -1) ?? 'a b c d';

        $Product = Product::with('ProductSize')
        ->where('state', 1)
        ->where('name', 'like', '%' . $name  . '%')
        ->orWhere('description', 'like', '%' . $name  . '%')
        ->when($request->has('discount'), function ($query) use ($request) {
            $query->orWhereBetween('discount', [0,$request->discount]);
        })
        ->when($request->has('size'), function ($query) use ($request) {
                $query->orWhereBetween('size', [0,$request->size]);
        })
        ->when($request->has('quantity'), function ($query) use ($request) {
            $query->orWhereBetween('quantity', [0,$request->size]);
        })
        ->when($request->has('price'), function ($query) use ($request) {
                $query->orWhereBetween('price', [0,$request->size]);
        })
        ->get();

        return $this->apiResponse(200, "Products", null, ProductResource::collection($Product));
    }
    ///////////////////////////////////////
    public function AdminProduct()
    {
        $Product=Product::get();
        return $this->apiResponse(200, "Products", null, $Product);
    }
    public function AddProduct(Request $request)
    {
        $validations = Validator::make($request->all(), [
            'name' => 'required|array',
            'name.*' => 'string',
            'description' => 'required|array',
            'description.*' => 'string',
            'category_id' => 'required|exists:Categories,id',
            'discount' => 'required|numeric',
            'state' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
            'quantity' => 'required|numeric|min:1',
            'price' => 'required|numeric',
        ]);

        if($validations->fails()) {
            return $this->apiResponse(400, 'validation error', $validations->errors());
        }
        Product::create([
        'name' => $request->name,
        'description' =>  $request->description,
        'category_id' => $request->category_id,
        'discount' =>  $request->discount,
        'state' =>  $request->state,
        'quantity' =>  $request->quantity,
        'price' =>  $request->price,
        'image' =>  $this->AddImage("images", "products", $request->image),
        ]);
        $Product=Product::get();
        return $this->apiResponse(200, 'Product Created successfully', $Product);

    }
    //
    public function UpdateProduct(Request $request)
    {

        $validations = Validator::make($request->all(), [
        'id' => 'required|exists:Products,id',
        'name' => 'required|array',
        'name.*' => 'string',
        'description' => 'required|array',
        'description.*' => 'string',
        'category_id' => 'required|exists:Categories,id',
        'discount' => 'required|numeric',
        'state' => 'required',
        'quantity' => 'required|numeric|min:1',
        'price' => 'required|numeric',
        ]);
        if($validations->fails()) {
            return $this->apiResponse(400, 'validation error', $validations->errors());
        }

        DB::transaction(function () use ($request) {
            $Product= Product::where('id', $request->id)->first();
            $Product->update([
            'name' => $request->name,
            'description' =>  $request->description,
            'category_id' => $request->category_id,
            'discount' =>  $request->discount,
            'state' =>  $request->state,
            'quantity' =>  $request->quantity,
            'price' =>  $request->price,
            'updated_at	'=> time()
            ]);

            if ($request->image) {
                $Product->update([
                    'image' =>  $this->UpdateImage("images", "products", $request->image, $Product->image),
                ]);
            }
        });
        $Product=Product::get();
        return $this->apiResponse(200, 'update Product is done', $Product);
    }
    ///
    public function DeleteProduct(Request $request)
    {
        $validations = Validator::make($request->all(), [
        'id' => 'required|exists:Products,id'
        ]);
        if($validations->fails()) {
            return $this->apiResponse(400, 'validation error', $validations->errors());
        }

        $Product= Product::where('id', $request->id)->first();
        Storage::delete($Product->image);


        $Product->delete();
        $Product=Product::get();

        return $this->apiResponse(200, 'delete Product is done', $Product);
    }

    ////////////////////////////////////////////////////////
}
