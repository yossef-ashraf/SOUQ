<?php

namespace App\Http\Interfaces;

interface Title_complaintInterface
{
    public function Title_complaints();

    public function addToTitle_complaint($request);

    public function updateTitle_complaintByAdmin($request);

    public function deleteFromTitle_complaintByAdmin($request);

}
