<?php

namespace App\Transformers\API;

use App\Models\CommissionDetail;
use League\Fractal\TransformerAbstract;

class CommissionDetailTransformer extends TransformerAbstract
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
    public function transform(CommissionDetail $detail)
    {
        return [
            'min'       => $detail->min,
            'max'       => $detail->max,
            'percent'   => $detail->percent,
        ];
    }
}
