<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Transformers\API\EventTransformer;
use Illuminate\Http\Request;

class EventApiController extends Controller
{
    public function index(Request $request)
    {
        $event = fractal(Event::all(), new EventTransformer())->parseIncludes(['details']);        

        return $this->apiSuccess($event);

    }

    public function show($event)
    {
        $event = Event::find($event);
        
        if($event){
            
            $event = fractal($event, new EventTransformer())->parseIncludes(['details']);   
            return $this->apiSuccess($event);
        } else {
            
            return $this->apiError([], [], 'Event Not Found');
        }

    }
}
