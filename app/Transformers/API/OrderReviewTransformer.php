<?php

namespace App\Transformers\API;

use App\Models\OrderReview;
use League\Fractal\TransformerAbstract;

class OrderReviewTransformer extends TransformerAbstract
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
    public function transform(OrderReview $review)
    {
        return [
            
        ];
    }
}
