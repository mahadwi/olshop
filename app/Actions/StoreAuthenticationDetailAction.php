<?php

namespace App\Actions;

use App\Models\AuthenticationDetail;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;

class StoreAuthenticationDetailAction
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
        if($this->isImage($this->attributes['image'])){
            $file = (new UploadService())->saveFile($this->attributes['image']);

            $this->attributes['image'] = $file['name'];
        }
        $authenticationDetail = new AuthenticationDetail($this->attributes);
        $authenticationDetail->save();

        return $authenticationDetail;
    }
}
