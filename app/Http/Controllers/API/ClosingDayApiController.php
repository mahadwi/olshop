<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\OpenDayApiRequest;

class ClosingDayApiController extends Controller
{
    public function open(OpenDayApiRequest $request)
    {
        //store order
        // $order = (new StoreOrderApiAction($request->all()))->handle();
        
        // $order = fractal($order, new OrderTransformer);           
            
        return $this->apiSuccess();
    }
}
