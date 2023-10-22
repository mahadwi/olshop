<?php

namespace App\Actions;

use App\Models\Product;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;


class UpdateProductAction
{
    private $product;
    private $attributes;


    public function __construct(Product $product, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->product = $product;
    }

    public function handle()
    {   
        if($this->attributes['image']){
            $oldFile = public_path('image/product/'.$this->product->image);

            if(File::exists($oldFile)){       
                //delete file
                File::delete($oldFile);
            }

            $file = (new UploadService())->saveFile($this->attributes['image'], 'product');  

            $this->attributes['image'] = $file['name'];

        } else {
            unset($this->attributes['image']);
        }

        $this->product->fill($this->attributes)->save();

        return $this->product;
    }
}
