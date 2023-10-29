<?php

namespace App\Transformers\API;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{

    private $params;

    public function __construct(array $params = [])
    {
        $this->params = $params;
        // $this->city = City::find($params['city_id']);

        // $this->dateBooking = Carbon::parse($params['datetime_start'])->format('Y-m-d');
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
            'color'         => $product->color,
            'history'       => $product->history,
            'category'      => $product->productCategory,
            'brand'         => $product->brand,
            'stock'         => $product->stock,
            'sale_price'    => $product->sale_price        
        ];
    }
}
