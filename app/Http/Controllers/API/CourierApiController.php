<?php

namespace App\Http\Controllers\API;

use App\Constants\Courier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourierApiController extends Controller
{
    public function index()
    {
        $data = Courier::getValues();

        return $this->apiSuccess($data);
    }
}
