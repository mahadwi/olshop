<?php

namespace App\Transformers\API;

use App\Models\Vendor;
use League\Fractal\TransformerAbstract;

class VendorTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Vendor $vendor)
    {
        return [
            'id'                    => $vendor->id,
            'name'                  => $vendor->name,                 
            'email'                 => $vendor->email,                 
            'phone'                 => $vendor->phone,                 
            'address'               => $vendor->address,                 
            'ktp'                   => $vendor->ktp,
            'bank'                  => $vendor->bank,
            'bank_account_holder'   => $vendor->bank_account_holder,
            'bank_account_number'   => $vendor->bank_account_number,
            
        ];
    }
}
