<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SubscribeSplash;
use Illuminate\Http\Request;

class SubscribeApiController extends Controller
{
    public function index()
    {
        $subscribe = SubscribeSplash::select('id', 'title', 'image')->OrderBy('title', 'asc')->get();

        return $this->apiSuccess($subscribe);
    }
}
