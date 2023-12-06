<?php

namespace App\Transformers\API;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'product', 'user'
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
    public function transform(Cart $cart)
    {
        return [
            'id'            => $cart->id,
            'qty'           => $cart->qty,
            'price'         => $cart->product->sale_price,
            'total_price'   => $cart->qty * $cart->product->sale_price,
        ];
    }

    public function includeProduct($cart)
    {
        $product = $cart->product;
        if ($product instanceof Product) {
            return $this->item($product, new ProductTransformer());
        } else {
            return $this->null();
        }
    }

    public function includeUser($cart)
    {
        $user = $cart->user;
        if ($user instanceof User) {
            return $this->item($user, new UserTransformer());
        } else {
            return $this->null();
        }
    }
}
