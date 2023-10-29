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

    public function handle()
    {

        $product = new Product($this->attributes);
        
        $product->save();

        return $product;
    }
}
