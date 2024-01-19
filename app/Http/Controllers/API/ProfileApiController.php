<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileApiController extends Controller
{
    public function __invoke()
    {
        $data = Profile::with('subdistrict.city.province')->first();        
        
        return $this->apiSuccess($data);

    }
}
