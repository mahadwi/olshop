<?php

namespace App\Actions;

use App\Models\Product;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;

class StoreProductAction
{
    private $attributes;
    
    public function __construct(array $attributes = [])
    {
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

        $product = new Product($this->attributes);
        
        $product->save();

        return $product;
    }
}
