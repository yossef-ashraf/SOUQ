<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function return_success($message, $data): JsonResponse
    {
        return response()->json([
            'message'   => $message,
            'validation'=> [],
            'data'      => $data,
            'code'      => 200
        ], 200);
    }

    public function return_fail($message, $validation): JsonResponse
    {
        return response()->json([
            'message'   => $message,
            'validation'=> $validation,
            'data'      => [],
            'code'      => 400
        ], 400);
    }
}
