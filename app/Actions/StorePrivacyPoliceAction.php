<?php

namespace App\Actions;

use App\Models\PrivacyPolice;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;

class StorePrivacyPoliceAction
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

        $privacyPolice = new PrivacyPolice($this->attributes);

        $privacyPolice->save();

        return $privacyPolice;
    }
}
