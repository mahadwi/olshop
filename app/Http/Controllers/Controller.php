<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function apiSuccess($data = [], $meta = [], $message = 'success', int $code = 200)
    {
        return response(
            [
                'data' => $data,
                'meta' => $meta,
                'message' => $message,
                'errorCode' => 0
            ],
            $code
        );
    }
    
    protected function apiError($data = [], $meta = [], $message = 'Error', int $code = 422)
    {
        return response()->json(
            [
                'data' => $data,
                'meta' => $meta,
                'message' => $message,
                'errorCode' => $code
            ],
            $code
        );
    }
}
