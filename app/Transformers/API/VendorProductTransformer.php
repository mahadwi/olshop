<?php

namespace App\Transformers\API;

use App\Models\VendorProduct;
use League\Fractal\TransformerAbstract;

class VendorProductTransformer extends TransformerAbstract
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
    public function transform(VendorProduct $product)
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
            'sale_price'    => $product->sale_price,
            'sale_usd'      => $product->sale_usd,
            'condition'     => $product->condition,
            'weight'        => $product->weight,
            'height'        => $product->height,
            'width'         => $product->width,
            'length'        => $product->length,
            'status'        => $product->status,
            'offered_date'  => $product->created_at->format(config('app.default.datetime_human')),

            'images'        => $this->images($product),
        ];
    }

    private function images($product)
    {
        return is_null($product->imageable) ? [] : $product->imageable->map(function ($item) {
            return asset('image/'.$item->name);
        });
    }

}
