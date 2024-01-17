<?php

namespace App\Actions\API;

use App\Models\VendorProduct;
use Illuminate\Support\Facades\DB;

class StoreVendorProductAction
{
    private $attributes;
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        return DB::transaction(function () {
            
            $product = new VendorProduct($this->attributes);
        
            $product->save();

            dd($product);
            // //save image
            // foreach($this->attributes['image'] as $image){
            //     dispatch_sync(new StoreProductImageAction($product, ['image' => $image]));
            // }

            return $product;
        });
        
    }
}