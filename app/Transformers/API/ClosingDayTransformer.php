<?php

namespace App\Transformers\API;

use App\Models\ClosingDay;
use League\Fractal\TransformerAbstract;

class ClosingDayTransformer extends TransformerAbstract
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
    public function transform(ClosingDay $closing)
    {
        return [
            'open'          => $closing->open,
            'starting_cash' => $closing->starting_cash,
        ];
    }
}
