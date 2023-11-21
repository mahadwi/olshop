<?php

namespace App\Transformers\API;

use App\Models\Subdistrict;
use League\Fractal\TransformerAbstract;

class SubdistrictTransformer extends TransformerAbstract
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
    public function transform(Subdistrict $subdistrict)
    {
        return [
            'id'        => $subdistrict->id,
            'name'      => $subdistrict->name,                 
            'fullname'  => $this->getFullname($subdistrict),                 
        ];
    }

    public function getFullname($subdistrict)
    {
        return $subdistrict->city->province->name.', '.$subdistrict->city->name.', '.$subdistrict->name;
    }
}
