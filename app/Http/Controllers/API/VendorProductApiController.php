<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\API\StoreVendorProductAction;
use App\Http\Requests\API\StoreVendorProductRequest;

class VendorProductApiController extends Controller
{
    public function store(StoreVendorProductRequest $request)
    {
        $product = (new StoreVendorProductAction($request->all()))->handle();
        
        dd($product);
        $product = fractal($product, new BookingTransformer)
            ->parseIncludes(['payment']);
            
        return $this->apiSuccess($product);
    }
}
