<?php

namespace App\Http\Interfaces;

interface UserInterface
{
    public function users();

    public function updateuser( $request);

    public function updateuserByAdmin( $request);

    public function deleteuser($request);
}
