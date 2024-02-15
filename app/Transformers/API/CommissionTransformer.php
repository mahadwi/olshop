<?php

namespace App\Transformers\API;

use App\Models\Commission;
use League\Fractal\TransformerAbstract;

class CommissionTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'details'
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
    public function transform(Commission $commission)
    {
        return [
            'id'            => $commission->id,
            'brand'         => $commission->brand->name,
            'category'      => $commission->category,
        ];
    }

    public function includeDetails($commission)
    {        
        return $this->collection($commission->details, new CommissionDetailTransformer);
    }
}
