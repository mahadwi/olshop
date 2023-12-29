<?php

namespace App\Transformers\API;

use App\Models\ConsignmentCard;
use League\Fractal\TransformerAbstract;

class ConsignmentCardTransformer extends TransformerAbstract
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
    public function transform(ConsignmentCard $consignmentCard)
    {
        return [
            'id'                => $consignmentCard->id,
            "title"             => $consignmentCard->title,
            "title_en"          => $consignmentCard->title_en,
            "description"       => $consignmentCard->description,
            "description_en"    => $consignmentCard->description_en,                            
            "icon"              => $consignmentCard->icon ? $consignmentCard->icon_url : '',                            
        ];
    }
}
