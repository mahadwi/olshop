<?php

namespace App\Actions;

use App\Models\Profile;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;

class StoreProfileAction
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

        if($this->isImage($this->attributes['logo'])){
            $file = (new UploadService())->saveFile($this->attributes['logo']);  

            $this->attributes['logo'] = $file['name'];
        }

        $model = new Profile($this->attributes);
        
        $model->save();

        return $model;
    }
}