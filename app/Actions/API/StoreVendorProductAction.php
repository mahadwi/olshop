<?php

namespace App\Actions\API;

use App\Models\VendorProduct;
use App\Actions\StoreImageAction;
use Illuminate\Support\Facades\DB;
use App\Services\File\UploadService;
use App\Constants\VendorProductStatus;

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
            
            $this->attributes['status'] = VendorProductStatus::REVIEW;

            $product = new VendorProduct($this->attributes);
        
            $product->save();

            if(count($this->attributes['image']) > 0){
                //upload gambar
                foreach($this->attributes['image'] as $image){
                    $file = (new UploadService())->saveFile($image);
                    
                    $attributes = ['name' => $file['name']];

                    dispatch_sync(new StoreImageAction($attributes, $product));

                }
            }

            return $product;
        });
        
    }
}