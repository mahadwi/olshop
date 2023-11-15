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
            'color'         => $product->color->name,
            'history'       => $product->history,
            'category'      => $product->productCategory->name,
            'brand'         => $product->brand->name,
            'stock'         => $product->stock,
            'sale_price'    => $product->sale_price,
            'condition'     => $product->condition,
            'is_wishlist'   => false,
            'is_new_arrival'=> $product->entry_date->diffInMonths(Carbon::now()) > 0 ? false : true,
            'entry_date'    => $product->entry_date,
            'images'        => $this->images($product),        
        ];
    }

    private function images($product)
    {
        return is_null($product->images) ? [] : $product->images->map(function ($item) {
            return asset('image/product/'.$item->name);
        });
    }
}
