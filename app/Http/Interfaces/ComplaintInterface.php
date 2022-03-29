<?php

namespace App\Http\Interfaces;

interface ComplaintInterface
{
    public function Complaints();

    public function userComplaints();

    public function addToComplaint($request);

    public function deleteFromComplaint($request);

    public function deleteFromComplaintByAdmin($request);

}
