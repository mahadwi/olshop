<?php

namespace App\Http\Controllers\API;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\API\BannerTransformer;

class BannerApiController extends Controller
{
    public function index()
    {
        $banner = fractal(Banner::all(), new BannerTransformer())->toArray();

        return $this->apiSuccess($banner['data']);
    }
}
