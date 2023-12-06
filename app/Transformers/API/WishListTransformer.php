<?php

namespace App\Transformers\API;

use App\Models\User;
use App\Models\Product;
use App\Models\Wishlist;
use League\Fractal\TransformerAbstract;

class WishListTransformer extends TransformerAbstract
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
    public function transform(Wishlist $wishlist)
    {
        return [
            'id' => $wishlist->id
        ];
    }

    public function includeProduct($wishlist)
    {
        $product = $wishlist->product;
        if ($product instanceof Product) {
            return $this->item($product, new ProductTransformer());
        } else {
            return $this->null();
        }
    }

    public function includeUser($wishlist)
    {
        $user = $wishlist->user;
        if ($user instanceof User) {
            return $this->item($user, new UserTransformer());
        } else {
            return $this->null();
        }
    }
}
