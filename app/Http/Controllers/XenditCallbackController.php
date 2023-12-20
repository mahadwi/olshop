<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Xendit\XenditService;
use Exception;

class XenditCallbackController extends Controller
{
    public function callback(Request $request)
    {
        if ($request->header('x-callback-token') !== config('app.default.xendit_callback_token')) {
            return $this->apiError([], [], 'Not authorize access', 403);
        }

        try {
            XenditService::callbackPaid($request->all());

            return $this->apiSuccess();
        } catch (Exception $exception) {
            return $this->apiError([], [], $exception->getMessage(), 422);
        }
    }
}
