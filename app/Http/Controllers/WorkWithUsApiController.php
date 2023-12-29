<?php

namespace App\Http\Controllers;

use App\Models\WorkWithUs;
use App\Transformers\API\WorkWithUsTransformer;
use Illuminate\Http\Request;

class WorkWithUsApiController extends Controller
{
    public function index()
    {
        $data = WorkWithUs::with('workWithUsDetail.workWithUsCard')->get();
        $workWithUs = fractal($data, new WorkWithUsTransformer)->toArray();
        return $this->apiSuccess($workWithUs);
    }
}
