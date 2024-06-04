<?php

namespace App\Http\Controllers\Web;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function show()
    {
     $Product= Product::get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Product);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:products,id',
        ]);
        if ($validator->fails())
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());

        $Product= Product::where('id', $request->id)
        ->with(['myComment','myFav','category','brand','blogs','comments','product_details','product_details.product_sizes','product_details.product_images'])
        ->first();
        $Product->update([
            'views' => $Product->views + 1
        ]);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Product);
    }
    public function showNew()
    {
     $Product= Product::orderBy('created_at', 'desc')->take(15)->get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Product);
    }
    public function showOffer()
    {
     $Product=  Product::whereNotNull('discount')->orderBy('updated_at', 'desc')->get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Product);
    }
    public function showCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
        ]);
        if ($validator->fails())
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());

        $Product= Product::where('category_id', $request->category_id)
        ->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Product);
    }
    public function showBrand(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brand_id' => 'required|exists:brands,id',
        ]);
        if ($validator->fails())
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());

        $Product= Product::where('brand_id', $request->brand_id)
        ->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Product);
    }

    public function search(Request $request)
    {
        $query = Product::query();

        // Search by product attributes
        if ($request->has('name')) {
            $name=substr(json_encode($request->name), 1, -1) ?? 'a b c d';
            $query->where('name', 'like', '%' . $name . '%');
        }
        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }
        if ($request->has('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }
        if ($request->has('discounted')) {
            // Search for products with discounts
            $query->where(function ($subQuery) {
                $subQuery->where('discount', '>', 0)
                        ->orWhere('has_discount_category', true)
                        ->orWhere('has_discount_brand', true);
            });
        }
        // Search by product detail attributes
        if ($request->has('color')) {
            $query->whereHas('product_details', function ($subQuery) use ($request) {
                $subQuery->where('color', $request->input('color'));
            });
        }
        // Search by price range
        if ($request->has('min_price')) {
            $query->whereHas('product_details.product_sizes', function ($subQuery) use ($request) {
                $subQuery->where('price', '>=', $request->input('min_price'));
            });
        }
        if ($request->has('max_price')) {
            $query->whereHas('product_details.product_sizes', function ($subQuery) use ($request) {
                $subQuery->where('price', '<=', $request->input('max_price'));
            });
        }
        // Search by product size attributes
        if ($request->has('size')) {
            $query->whereHas('product_details.product_sizes', function ($subQuery) use ($request) {
                $subQuery->where('size', $request->input('size'));
            });
        }
        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        $products = $query->get();

        return $this->apiResponse(200, __('lang.Successfully'), null, $products);
    }

}
