<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqApiController extends Controller
{
    public function index()
    {
        $faq = Faq::all();

        return $this->apiSuccess($faq);
    }
}
