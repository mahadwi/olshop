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
        $fileKeys = array_keys(array_filter([
            'file' => $this->attributes['file'],
            'file_en' => $this->attributes['file_en'],
        ], 'is_file')); // Use a built-in function for existence and file type check

        foreach ($fileKeys as $fileKey) {
            $filePath = $this->attributes[$fileKey];

            // Upload and update attribute directly within the loop
            $this->attributes[$fileKey] = (new UploadService())->uploadFile($filePath)['name'];

        }
        

        $model = new Agreement($this->attributes);
        
        $model->save();

        return $model;
    }
}