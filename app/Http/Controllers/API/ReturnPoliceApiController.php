<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ReturnPolice;
use Illuminate\Http\Request;

class ReturnPoliceApiController extends Controller
{
    public function index()
    {
        $returnPolice = ReturnPolice::get();

        return $this->apiSuccess($returnPolice);
    }
}
