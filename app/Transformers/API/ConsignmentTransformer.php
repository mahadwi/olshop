<?php

namespace App\Transformers\API;

use App\Models\Consignment;
use League\Fractal\TransformerAbstract;

class ConsignmentTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'sections'        
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        // 'sections'
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Consignment $consignment)
    {
        return [
            "id" => $consignment->id,
            "title" => $consignment->title,
            "title_en" => $consignment->title_en,
            "description" => $consignment->description,
            "description_en" => $consignment->description_en,
        ];
    }

    public function includeSections($consignment)
    {        
        return $this->collection($consignment->consignmentDetail, new ConsignmentDetailTransformer);
    }
}
