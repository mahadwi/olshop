<?php

namespace App\Actions;

use App\Models\Rekening;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;

class StoreRekeningAction
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

        $rekening = new Rekening($this->attributes);
        
        $rekening->save();

        return $rekening;
    }
}