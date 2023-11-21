<?php

namespace App\Actions;

use App\Models\Brand;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;


class UpdateBrandAction
{
    private $brand;
    private $attributes;


    public function __construct(Brand $brand, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->brand = $brand;
    }

    public function handle()
    {   
        
        if($this->attributes['image']){
            $oldFile = public_path('image/brand/'.$this->brand->image);

            if(File::exists($oldFile)){       
                //delete file
                File::delete($oldFile);
            }

            $file = (new UploadService())->saveFile($this->attributes['image'], 'brand');  

            $this->attributes['image'] = $file['name'];

        } else {
            unset($this->attributes['image']);
        }

        $this->brand->fill($this->attributes)->save();

        return $this->brand;
    }
}
