<?php

namespace App\Transformers;

use App\Models\ProductImage;
use League\Fractal\TransformerAbstract;

class ProductImageTransformer extends TransformerAbstract
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
    public function transform(ProductImage $productImage)
    {
        return [
            'id'    => $productImage->id,
            'url'   => asset('image/product/'.$productImage->name)
        ];
    }
}
