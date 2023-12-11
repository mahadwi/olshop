<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandApiController extends Controller
{
    public function index()
    {
        $brand = Brand::select('id', 'name', 'image', 'description', 'description_en')->OrderBy('name', 'asc')->get();

        return $this->apiSuccess($brand);
    }
}
