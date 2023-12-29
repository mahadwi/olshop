<?php

namespace App\Transformers\API;

use App\Models\ConsignmentDetail;
use League\Fractal\TransformerAbstract;

class ConsignmentDetailTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'cards'
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
    public function transform(ConsignmentDetail $consignmentDetail)
    {
        return [
            'id'                => $consignmentDetail->id,
            'section'           => $consignmentDetail->section,
            "title"             => $consignmentDetail->title,
            "title_en"          => $consignmentDetail->title_en,
            "description"       => $consignmentDetail->description,
            "description_en"    => $consignmentDetail->description_en,                            
            "image"             => $consignmentDetail->image ? $consignmentDetail->image_url : '',                            
            "link"              => $consignmentDetail->link,                            
        ];
    }

    public function includeCards($consignmentDetail)
    {        
        return $this->collection($consignmentDetail->consignmentCard, new ConsignmentCardTransformer);
    }
}
