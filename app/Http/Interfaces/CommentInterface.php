<?php

namespace App\Http\Interfaces;

interface CommentInterface
{
    public function Comments();

    public function userComments();

    public function productComments($request);

    public function addToComment($request);

    public function UpdateComment($request);

    public function deleteFromComment($request);

    public function deleteFromCommentByAdmin($request);

}
