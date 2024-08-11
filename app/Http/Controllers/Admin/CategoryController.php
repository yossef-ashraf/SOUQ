<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function show()
    {
     $Category= Category::get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Category);
    }
    public function showAll()
    {
        $Category=  Category::withTrashed()->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Category);
    }
    public function showTrashed()
    {
        $Category= Category::onlyTrashed()->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Category);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:categories,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Category = Category::withTrashed()->findOrFail($request->id);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Category);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|array',
            'img' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'offer'=> 'numeric',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Category = Category::create([
            'name'=>$request->name,
            'offer'=>$request->offer,
            'img'=>$this-> AddFileInPublic('Images','Category',$request->img),
        ]);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Category);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'   => 'required|exists:categories,id',
            'name' => 'required|array',
            'offer'=> 'numeric',
            'img'  => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Category = Category::withTrashed()->findOrFail($request->id);
        DB::transaction(function () use(&$Category , $request){
                $Category->update([
                    'name'=>$request->name,
                    'offer'=>$request->offer,
                ]);

                if ($request->img) {
                    $Category->update([
                        'img'=>$this-> UpdateFileInPublic('Images','Category',$request->img , $Category->img ),
                    ]);
                }
        });
        return $this->apiResponse(200,__('lang.Successfully'),null,$Category);
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:categories,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Category = Category::findOrFail($request->id);
        $Category->delete();
        return $this->apiResponse(200,__('lang.Successfully'));

    }
    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:categories,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Category = Category::onlyTrashed()->findOrFail($request->id);
        $Category->restore();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Category);
    }
    public function forceDelete(Request $request)
    {
        // dd('ff');
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:categories,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Category = Category::withTrashed()->findOrFail($request->id);
        unlink(public_path($Category->img));
        $Category->forceDelete();
        return $this->apiResponse(200,__('lang.Successfully'));
    }
}
