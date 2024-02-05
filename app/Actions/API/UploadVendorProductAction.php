<?php

namespace App\Actions\API;

use App\Models\VendorProduct;
use Illuminate\Support\Facades\DB;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;

class UploadVendorProductAction
{
    public function handle(VendorProduct $product, array $attributes = [])
    {
        return DB::transaction(function () use ($product, $attributes) {

            //upload file                  
            $type = $attributes['type'] . '_file';
            $oldFile = public_path('file/'.$product->{$type});

            if(File::exists($oldFile)){       
                //delete file
                File::delete($oldFile);
            }

            $file = (new UploadService())->uploadFile($attributes['file']);  

            $attributes[$type] = $file['name'];
    
            $product->fill($attributes);
            $product->save();

            return $product;
            
        });
    }
}