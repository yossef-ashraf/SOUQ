<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
use ApiResponseTrait;

public function AddComment(Request $request)
{
    $validations = Validator::make($request->all(),[
        'product_id' => 'required|exists:Products,id',
        'comment' => 'required',
        ]);

        if($validations->fails())
        {
        return $this->apiResponse(400, 'validation error', $validations->errors());
        }

        Comment::create([
        'user_id' => Auth::user()->id,
        'product_id' => $request->product_id,
        'comment' => $request->comment,
        'status' => 'Pending',
        ]);
        $Comment = Comment::with('User','Product')->get();
        return $this->apiResponse(200, 'your comment now is pending by admin when Approved your comment then will appear',$Comment);
}

public function DeleteCommentByUser(Request $request)
{
    $validations = Validator::make($request->all(),[
    'id' => 'required|exists:Comments,id',
    ]);

    if($validations->fails())
    {
    return $this->apiResponse(400, 'validation error', $validations->errors());
    }

    $Comment=Comment::where([['id', $request->id ],['user_id', auth()->user()->id ]])->first();
    if ($Comment) {
    $Comment->delete();
    $Comment=Comment::where([['product_id',$request->product_id],['status','Approved']])->with('User')->get();
    return $this->apiResponse(200,"delete Done",$Comment);
    }
    return $this->apiResponse(400,"you can delete Done");
}
public function MyComments()
{
        $Comment=Comment::where( 'user_id' , Auth::user()->id)->with('Product')->get();
        return $this->apiResponse(200,"Comments",null,$Comment);

}

public function Comments(Request $request)
{
    $validations = Validator::make($request->all(),[
        'product_id' => 'required|exists:Products,id',
        ]);
        if($validations->fails())
        {
        return $this->apiResponse(400, 'validation error', $validations->errors());
        }
        $Comment=Comment::where([['product_id',$request->product_id],['status','Approved']])->with('User')->get();
        return $this->apiResponse(200,"Comments",null,$Comment);

}

public function AdminComment()
{
    $Comment = Comment::with('User','Product')->get();
    return $this->apiResponse(200," all commends ",null,$Comment);
}

public function CommentState(Request $request)
{
    try {
        $validations = Validator::make($request->all(),[
            'id' => 'required|exists:Comments,id',
            'status' => 'required',
            ]);

            if($validations->fails())
            {
            return $this->apiResponse(400, 'validation error', $validations->errors());
            }

        if ($request->status == 'Rejected') {
            $Comment=Comment::where('id', $request->id )->first();
            $Comment->delete();
            return $this->apiResponse(200,"delete Done");
            # code...
        }
            $Comment=Comment::where('id', $request->id )->first();
            $Comment->update([
                'status' => $request->status
                ]);
                $Comment = Comment::with('User','Product')->get();
                return $this->apiResponse(200,"Done",$Comment);
    } catch (\Throwable $th) {
        //throw $th;
        return $this->apiResponse(400,throw $th);
    }


}

public function DeleteCommentByAdmin(Request $request)
{
    $validations = Validator::make($request->all(),[
    'id' => 'required|exists:Comments,id',
    ]);

    if($validations->fails())
    {
    return $this->apiResponse(400, 'validation error', $validations->errors());
    }

    $Comment=Comment::where('id', $request->id )->first();
    $Comment->delete();
    $Comment = Comment::with('User','Product')->get();
    return $this->apiResponse(200,"delete Done",$Comment);
}


}
