<?php

namespace App\Actions;

use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;

class StoreBannerAction
{
    private $attributes;
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        return DB::transaction(function () {

           
               
            $banner = new Banner(collect($this->attributes)->except('image')->all());            
            $banner->save();

            if(count($this->attributes['image']) > 0){
                //upload gambar
                foreach($this->attributes['image'] as $image){
                    $file = (new UploadService())->saveFile($image);
                    
                    $attributes = ['name' => $file['name']];

                    dispatch_sync(new StoreImageAction($attributes, $banner));

                }
            }

            return $banner;
        });
       
    }
}
