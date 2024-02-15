<?php

namespace App\Http\Controllers\API;

use App\Models\Commission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\API\CommissionTransformer;

class CommissionApiController extends Controller
{
    public function index()
    {
        $data = fractal(Commission::all(), new CommissionTransformer()); 
        
        return $this->apiSuccess($data);
    }
}
