<?php

namespace App\Actions;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
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
        return DB::transaction(function () {
            
            $product = new Product($this->attributes);
        
            $product->save();

            //save image
            foreach($this->attributes['image'] as $image){
                dispatch_sync(new StoreProductImageAction($product, ['image' => $image]));
            }

            return $product;
        });
        
    }
}
