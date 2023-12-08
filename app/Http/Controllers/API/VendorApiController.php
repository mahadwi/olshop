<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreVendorApiRequest;
use App\Models\Vendor;
use App\Transformers\API\VendorTransformer;
use Illuminate\Http\Request;

class VendorApiController extends Controller
{
    public function store(StoreVendorApiRequest $request)
    {
        $store = new Vendor($request->all());
        
        $store->save();

        $vendor = fractal($store, new VendorTransformer())->toArray();

        return $this->apiSuccess($vendor);
    }
}
