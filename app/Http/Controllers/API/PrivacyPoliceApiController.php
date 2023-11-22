<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolice;
use Illuminate\Http\Request;

class PrivacyPoliceApiController extends Controller
{
    public function index()
    {
        $abouts = PrivacyPolice::get();

        return $this->apiSuccess($abouts);
    }
}
