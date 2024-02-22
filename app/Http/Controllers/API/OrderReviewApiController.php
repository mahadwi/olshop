<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\API\OrderTransformer;
use App\Actions\API\StoreOrderReviewAction;
use App\Http\Requests\API\OrderReviewApiRequest;

class OrderReviewApiController extends Controller
{
    public function store(OrderReviewApiRequest $request)
    {
        
        // store review
        $review = (new StoreOrderReviewAction($request->all()))->handle();
        
        return $this->apiSuccess();

    }
}
