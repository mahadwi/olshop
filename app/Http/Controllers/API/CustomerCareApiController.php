<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CustomerCare;
use Illuminate\Http\Request;

class CustomerCareApiController extends Controller
{
    public function index()
    {
        $customerCare = CustomerCare::get();
        return $this->apiSuccess($customerCare);
    }
}
