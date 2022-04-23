<?php

namespace App\Http\Interfaces;

interface ComplaintInterface
{
    public function Complaints();

    public function addToComplaint($request);

    public function deleteFromComplaint($request);

}
