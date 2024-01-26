<?php

namespace App\Http\Controllers\API;

use App\Actions\UpdateVendorAction;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\API\VendorTransformer;
use App\Http\Requests\API\UpdateVendorApiRequest;
use App\Http\Requests\API\StoreVendorApiRequest;

class VendorApiController extends Controller
{

    public function index()
    {
        $vendor = Vendor::where('user_id', auth()->user()->id)->get();

        $vendor = fractal($vendor, new VendorTransformer())->toArray();

        return $this->apiSuccess($vendor);
    }

    public function store(StoreVendorApiRequest $request)
    {
        $store = new Vendor($request->all());
        
        $store->save();

        $vendor = fractal($store, new VendorTransformer())->toArray();

        return $this->apiSuccess($vendor);
    }

    public function update(UpdateVendorApiRequest $request, Vendor $vendor)
    {
        $data = dispatch_sync(new UpdateVendorAction($vendor, $request->all()));

        $address = fractal($data, new VendorTransformer())->toArray();

        return $this->apiSuccess($address);
        
    }

    public function destroy($address)
    {        
        $delete = Vendor::find($address);
        if(!$delete){
            return $this->apiError([],[],'Vendor not found');
        }
        $delete->delete();

        return $this->apiSuccess();
    }
}
