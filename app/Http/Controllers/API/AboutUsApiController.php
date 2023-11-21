<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;

class AboutUsApiController extends Controller
{
    public function index()
    {
        $abouts = AboutUs::get();

        return $this->apiSuccess($abouts);
    }
}
