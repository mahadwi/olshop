<?php

namespace App\Http\Controllers\API;

use App\Models\Email;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\API\EmailTransformer;
use App\Http\Requests\API\StoreEmailSubscribeApiRequest;

class EmailSubscribeApiController extends Controller
{
    public function insert(StoreEmailSubscribeApiRequest $request)
    {
        $store = new Email($request->all());

        $store->save();

        $email = fractal($store, new EmailTransformer())->toArray();

        return $this->apiSuccess($email);
    }
}
