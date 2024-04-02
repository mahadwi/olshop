<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\API\StoreOpenDayAction;
use App\Actions\API\StoreClosingDayAction;
use App\Http\Requests\API\CloseDayRequest;
use App\Http\Requests\API\OpenDayApiRequest;
use App\Models\ClosingDay;
use App\Transformers\API\ClosingDayTransformer;

class ClosingDayApiController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');

        $closing = ClosingDay::whereDate('open', $today)->first();

        $closing = fractal($closing, new ClosingDayTransformer); 

        return $this->apiSuccess($closing);

    }

    public function open(OpenDayApiRequest $request)
    {
        //store closing day
        $model = (new StoreOpenDayAction($request->all()))->handle();
        
        $model = fractal($model, new ClosingDayTransformer);           
            
        return $this->apiSuccess($model);
    }

    public function close(CloseDayRequest $request)
    {
        //store closing day
        $model = (new StoreClosingDayAction($request->all()))->handle();
        
        $model = fractal($model, new ClosingDayTransformer);           
            
        return $this->apiSuccess($model);
    }
}
