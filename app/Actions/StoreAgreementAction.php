<?php

namespace App\Actions;

use App\Models\Agreement;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;


class StoreAgreementAction
{
    private $attributes;
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    private function isFile(object $file)
    {
        return $file instanceof File;
    }

    public function handle()
    {   
        if($this->attributes['file']){
            $file = (new UploadService())->uploadFile($this->attributes['file']);  

            $this->attributes['file'] = $file['name'];
        }

        $model = new Agreement($this->attributes);
        
        $model->save();

        return $model;
    }
}