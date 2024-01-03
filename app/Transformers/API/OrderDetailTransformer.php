<?php

namespace App\Transformers\API;

use App\Models\OrderDetail;
use App\Models\Product;
use League\Fractal\TransformerAbstract;

class OrderDetailTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'product'
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
    public function transform(OrderDetail $orderDetail)
    {
        return [
            'id'        => $orderDetail->id,
            'price'     => $orderDetail->price,            
            'qty'       => $orderDetail->qty,
            'total'     => $orderDetail->total,
        ];
    }

    public function includeProduct($orderDetail)
    {
        
        $product = $orderDetail->product;
        if ($product instanceof Product) {
            return $this->item($product, new ProductTransformer());
        } else {
            return $this->null();
        }
    }
}
