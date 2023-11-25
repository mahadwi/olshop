<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DeliveryShipping;
use Illuminate\Http\Request;

class DeliveryShippingApiController extends Controller
{
    public function index()
    {
        $deliveryShipping = DeliveryShipping::get();

        return $this->apiSuccess($deliveryShipping);
    }
}
