<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Transformers\API\GalleryTransformer;
use Illuminate\Http\Request;

class GalleryApiController extends Controller
{
    public function index()
    {
        $data = fractal(Gallery::all(), new GalleryTransformer);

        return $this->apiSuccess($data);
    }
}
