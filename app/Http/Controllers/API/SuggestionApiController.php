<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSuggestionApiRequest;
use App\Models\Suggestion;

class SuggestionApiController extends Controller
{
    public function store(StoreSuggestionApiRequest $request)
    {
        $store = new Suggestion($request->all());
        
        $store->save();

        return $this->apiSuccess($store);
    }
}
