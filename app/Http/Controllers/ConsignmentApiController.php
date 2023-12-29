<?php

namespace App\Http\Controllers;

use App\Models\Consignment;
use App\Transformers\API\ConsignmentTransformer;
use Illuminate\Http\Request;

class ConsignmentApiController extends Controller
{
    public function index()
    {
        $data = Consignment::with('consignmentDetail.consignmentCard')->get();
        $authentication = fractal($data, new ConsignmentTransformer)->toArray();
        return $this->apiSuccess($authentication);
    }
}
