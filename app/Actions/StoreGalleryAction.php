<?php

namespace App\Actions;

use App\Models\Gallery;
use Illuminate\Support\Facades\DB;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;

class StoreGalleryAction
{
    private $attributes;
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        return DB::transaction(function () {

            $gallery = new Gallery(collect($this->attributes)->except('image')->all());            
            $gallery->save();

            if(count($this->attributes['image']) > 0){
                //upload gambar
                foreach($this->attributes['image'] as $image){
                    $file = (new UploadService())->saveFile($image);
                    
                    $attributes = ['name' => $file['name']];

                    dispatch_sync(new StoreImageAction($attributes, $gallery));

                }
            }

            return $gallery;
        });
       
    }
}
