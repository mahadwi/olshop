<?php

namespace App\Http\Controllers\API;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\API\MessageTransformer;
use App\Http\Requests\API\StoreMessageApiRequest;

class MessageApiController extends Controller
{
    public function insert(StoreMessageApiRequest $request)
    {
        $store = new Message($request->all());

        $store->save();

        $message = fractal($store, new MessageTransformer())->toArray();

        return $this->apiSuccess($message);
    }
}
