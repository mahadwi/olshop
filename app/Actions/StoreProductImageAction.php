<?php

namespace App\Actions;

use App\Models\Product;
use App\Models\ProductImage;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;

class StoreProductImageAction
{
    private $attributes;
    private $product;
    
    public function __construct(Product $product, array $attributes = [])
    {
        $this->product = $product;
        $this->attributes = $attributes;
    }

    private function isImage(object $file)
    {
        return $file instanceof File;
    }

    public function handle()
    {
        if($this->isImage($this->attributes['image'])){
            $file = (new UploadService())->saveFile($this->attributes['image'], 'product');  

            $this->attributes['image'] = $file['name'];
        }

        $insert = new ProductImage;
        $insert->product_id = $this->product->id;
        $insert->name = $this->attributes['image'];
        
        $insert->save();

    }
}
