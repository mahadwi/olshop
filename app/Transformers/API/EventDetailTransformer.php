<?php

namespace App\Transformers\API;

use App\Models\EventDetail;
use League\Fractal\TransformerAbstract;

class EventDetailTransformer extends TransformerAbstract
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
    public function transform(EventDetail $detail)
    {
        return [
            'name'          => $detail->name,
            'id'            => $detail->id,
            'date'          => $detail->date->format('d-m-y'),
            'time_start'    => $detail->time_start,
            'time_end'      => $detail->time_end,
            'contact'       => $detail->contact,
            'price'         => $detail->price,        
            'is_refundable'  => $detail->is_refundable,        
        ];
    }
}
