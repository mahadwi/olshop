<?php

namespace App\Http\Controllers\API;

use App\Actions\API\UpdateAddressApiAction;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreAddressApiRequest;
use App\Http\Requests\API\UpdateAddressApiRequest;
use App\Transformers\API\AddressTransformer;

class AddressApiController extends Controller
{
    public function store(StoreAddressApiRequest $request)
    {
        $store = new Address($request->all());
        
        $store->save();

        $address = fractal($store, new AddressTransformer())->toArray();

        return $this->apiSuccess($address);
    }

    public function update(UpdateAddressApiRequest $request, Address $address)
    {
        $data = dispatch_sync(new UpdateAddressApiAction($address, $request->all()));

        $address = fractal($data, new AddressTransformer())->toArray();

        return $this->apiSuccess($address);
        
    }

    public function destroy($address)
    {        
        $delete = Address::find($address);
        if(!$delete){
            return $this->apiError([],[],'Address not found');
        }
        $delete->delete();

        return $this->apiSuccess();
    }
}
