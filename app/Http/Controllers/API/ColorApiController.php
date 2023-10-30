<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorApiController extends Controller
{
    public function index()
    {
        $data = Color::select('id', 'name')->OrderBy('name', 'asc')->get();

        return $this->apiSuccess($data);
    }
}
