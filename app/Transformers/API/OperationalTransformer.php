<?php

namespace App\Transformers\API;

use App\Models\Operational;
use League\Fractal\TransformerAbstract;

class OperationalTransformer extends TransformerAbstract
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
    public function transform(Operational $operational)
    {
        return [
            'day'       => $operational->day,
            'open'      => $operational->open,
            'close'     => $operational->close,
            'is_open'   => $operational->is_open
        ];
    }
}
