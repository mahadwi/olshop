<?php

namespace App\Transformers\API;

use App\Models\Product;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{

    private $params;

    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

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
    public function transform(Product $product)
    {
        return [
            'id'            => $product->id,
            'name'          => $product->name,
            'description'   => $product->description,
            'description_en'=> $product->description_en,
            'color'         => $product->color->name,
            'history'       => $product->history,
            'history_en'    => $product->history_en,
            'category'      => $product->productCategory->name,
            'brand'         => $product->brand->name,
            'stock'         => $product->stock,
            'sale_price'    => $product->sale_price,
            'sale_usd'      => $product->sale_usd,
            'condition'     => $product->condition,
            'is_wishlist'   => isset($this->params['user_id']) ? $product->isWishlist($this->params['user_id']) : false,
            'is_new_arrival'=> $product->isNewArrival,
            'entry_date'    => $product->entry_date->format('d-m-Y'),
            'weight'        => $product->fixWeight,
            'images'        => $this->images($product),
            'wishlist'      => isset($this->params['user_id']) ? $this->getWishlist($product, $this->params['user_id']) : null,
        ];
    }

    private function images($product)
    {
        return is_null($product->images) ? [] : $product->images->map(function ($item) {
            return asset('image/product/'.$item->name);
        });
    }

    private function getWishlist($product, $user_id){
        return $product->wishlists->where('user_id', $user_id)->where('product_id', $product->id)->first();
    }
}
