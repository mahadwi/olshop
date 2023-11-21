<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryApiController extends Controller
{
    public function index()
    {
        $data = ProductCategory::select('id', 'name', 'is_active')
        ->active()
        ->OrderBy('name', 'asc')->get();

        return $this->apiSuccess($data);
    }
}
