<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function show()
    {
     $Blog= Blog::get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Blog);
    }
    public function showAll()
    {
        $Blog=  Blog::withTrashed()->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Blog);
    }
    public function showTrashed()
    {
        $Blog= Blog::onlyTrashed()->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Blog);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:blogs,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Blog = Blog::withTrashed()->findOrFail($request->id);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Blog);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|array',
            'img' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description'=> 'required|array',
            'content'=> 'required|array',
            'product_id'   => 'exists:products,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Blog = Blog::create([
            'title'=>$request->title,
            'description'=>$request->description,
            'content'=>$request->content,
            'product_id'=>$request->product_id,
            'img'=>$this-> AddFileInPublic('Images','Blog',$request->img),
        ]);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Blog);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'   => 'required|exists:blogs,id',
            'title' => 'required|array',
            'description'=> 'required|array',
            'content'=> 'required|array',
            'product_id'   => 'exists:Products,id',
            'img'  => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Blog = Blog::withTrashed()->findOrFail($request->id);
        DB::transaction(function () use(&$Blog , $request){
                $Blog->update([
                    'title'=>$request->title,
                    'description'=>$request->description,
                    'content'=>$request->content,
                    'product_id'=>$request->product_id,
                ]);

                if ($request->img) {
                    $Blog->update([
                        'img'=>$this-> UpdateFileInPublic('Images','Blog',$request->img , $Blog->img ),
                    ]);
                }
        });
        return $this->apiResponse(200,__('lang.Successfully'),null,$Blog);
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:blogs,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Blog = Blog::findOrFail($request->id);
        $Blog->delete();
        return $this->apiResponse(200,__('lang.Successfully'));

    }
    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:blogs,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Blog = Blog::onlyTrashed()->findOrFail($request->id);
        $Blog->restore();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Blog);
    }
    public function forceDelete(Request $request)
    {
        // dd('ff');
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:blogs,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Blog = Blog::withTrashed()->findOrFail($request->id);
        unlink(public_path($Blog->img));
        $Blog->forceDelete();
        return $this->apiResponse(200,__('lang.Successfully'));
    }
}
