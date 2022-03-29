<?php

namespace App\Http\Interfaces;

interface Answer_complaintInterface
{
    public function Answer_complaints();

    public function userAnswer_complaints($request);

    public function addToAnswer_complaint($request);

    public function updateAnswer_complaintByAdmin($request);

    public function deleteFromAnswer_complaintByAdmin($request);

}
