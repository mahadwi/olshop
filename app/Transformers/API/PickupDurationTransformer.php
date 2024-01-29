<?php

namespace App\Transformers\API;

use App\Models\Operational;
use App\Models\PickupDuration;
use League\Fractal\TransformerAbstract;

class PickupDurationTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'operational'
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
    public function transform(PickupDuration $pickupDuration)
    {
        return [
            'duration' => $pickupDuration->duration
        ];
    }

    public function includeOperational()
    {
        $operational = Operational::orderby('id')->get();
        return $this->collection($operational, new OperationalTransformer);
    }
}
