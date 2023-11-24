<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TermCondition;
use Illuminate\Http\Request;

class TermConditionApiController extends Controller
{
    public function index()
    {
        $termCondition = TermCondition::get();

        return $this->apiSuccess($termCondition);
    }
}
