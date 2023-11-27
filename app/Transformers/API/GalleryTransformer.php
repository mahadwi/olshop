<?php

namespace App\Transformers\API;

use App\Models\Gallery;
use App\Models\Product;
use League\Fractal\TransformerAbstract;

class GalleryTransformer extends TransformerAbstract
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
        'product'
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Gallery $gallery)
    {
        return [
            'section'       => $gallery->section,
            'title'         => $gallery->title,
            'images'        => $this->images($gallery),                   
        ];
    }

    private function images($gallery)
    {
        if($gallery->imageable->isEmpty()){
            return is_null($gallery->product->images) ? [] : $gallery->product->images->map(function ($item) {
                return asset('image/product/'.$item->name);
            }); 
        } else {
            return is_null($gallery->imageable) ? [] : $gallery->imageable->map(function ($item) {
                return asset('image/'.$item->name);
            });
        }
        

    }

    public function includeProduct($gallery)
    {

        $product = $gallery->product;
        if ($product instanceof Product) {
            return $this->item($product, new ProductTransformer());
        } else {
            return $this->null();
        }
        // return $this->collection($gallery->product, new ProductTransformer());
    }

}
