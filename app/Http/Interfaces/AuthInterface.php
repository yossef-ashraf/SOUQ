<?php

namespace App\Http\Interfaces;

interface AuthInterface
{
    public function login($request);

    public function register($request);

    public function auth();
}
