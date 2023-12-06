<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewApiController extends Controller
{
    public function index()
    {
        $review = Review::all();

        return $this->apiSuccess($review);
    }
}
