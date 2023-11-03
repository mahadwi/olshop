<?php

namespace App\Transformers\API;

use App\Models\Gallery;
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
        //
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
            'images'        => $this->images($gallery)        
        ];
    }

    private function images($gallery)
    {

        if($gallery->product_id){
            return is_null($gallery->product->images) ? [] : $gallery->product->images->map(function ($item) {
                return asset('image/product/'.$item->name);
            }); 
        } else {
            return is_null($gallery->imageable) ? [] : $gallery->imageable->map(function ($item) {
                return asset('image/'.$item->name);
            });
        }
        

    }

}
