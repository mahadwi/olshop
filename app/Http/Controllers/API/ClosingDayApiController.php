<?php

namespace App\Http\Controllers\API;

use App\Actions\API\StoreClosingDayAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\OpenDayApiRequest;
use App\Transformers\API\ClosingDayTransformer;

class ClosingDayApiController extends Controller
{
    public function open(OpenDayApiRequest $request)
    {
        //store closing day
        $model = (new StoreClosingDayAction($request->all()))->handle();
        
        $model = fractal($model, new ClosingDayTransformer);           
            
        return $this->apiSuccess($model);
    }
}
