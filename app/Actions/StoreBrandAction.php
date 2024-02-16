<?php

namespace App\Actions;

use App\Models\Brand;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;

class StoreBrandAction
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

        if($this->attributes['image'] AND $this->isImage($this->attributes['image'])){
            $file = (new UploadService())->saveFile($this->attributes['image'], 'brand');  

            $this->attributes['image'] = $file['name'];
        }

        $brand = new Brand($this->attributes);
        
        $brand->save();

        return $brand;
    }
}
