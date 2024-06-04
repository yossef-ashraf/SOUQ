<?php

namespace App\Http\Controllers\Web;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function show()
    {
     $Comment= Comment::where('user_id',auth()->user()->id)->with('product')->get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Comment);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:comments,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Comment= Comment::where([['user_id',auth()->user()->id],['id',$request->id]])->with('product')->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Comment);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'=> 'required|exists:products,id',
            'comment'=> 'required',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Comment = Comment::create([
            'product_id'=>$request->product_id,
            'comment'=>$request->comment,
            'user_id'=>auth()->user()->id,
        ]);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Comment);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:comments,id',
            'comment'=> 'required',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Comment = Comment::where([['user_id', auth()->User()->id],['id',$request->id]])->first();
        $Comment->update([
            'comment'=>$request->comment,
        ]);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Comment);

    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:comments,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Comment = Comment::where([['user_id', auth()->User()->id],['id',$request->id]])->first();
        $Comment->delete();
        return $this->apiResponse(200,__('lang.Successfully'),null,$Comment);

    }

}
