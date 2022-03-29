<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Interfaces\CommentInterface;

class Commentcontroller extends Controller
{

public $CommentInterface;
public function __construct(CommentInterface $CommentInterface)
{
$this->CommentInterface = $CommentInterface;
}

public function Comments()
{
return $this->CommentInterface->Comments();
}

public function userComments()
{
return $this->CommentInterface->userComments();
}

public function productComments(Request $request)
{
return $this->CommentInterface->productComments($request);
}

public function addToComment(Request $request)
{
return $this->CommentInterface->addToComment($request);
}

public function UpdateComment(Request $request)
{
return $this->CommentInterface->UpdateComment($request);
}

public function deleteFromComment(Request $request)
{
return $this->CommentInterface->deleteFromComment($request);
}

public function deleteFromCommentByAdmin(Request $request)
{
return $this->CommentInterface->deleteFromComment($request);
}

}
