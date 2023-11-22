<?php

namespace App\Transformers\API;

use App\Models\Address;
use League\Fractal\TransformerAbstract;

class AddressTransformer extends TransformerAbstract
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
    public function transform(Address $address)
    {
        return [
            'id'            => $address->id,
            'name'          => $address->name,                 
            'address'       => $address->address,                 
            'phone'         => $address->phone,
            'tag'           => $address->tag,
            'is_primary'    => $address->is_primary,
            'full_address'  => $address->full_address,
        ];
    }
  
}
