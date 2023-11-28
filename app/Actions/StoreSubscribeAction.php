<?php

namespace App\Actions;

use App\Models\SubscribeSplash;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;

class StoreSubscribeAction
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
            $file = (new UploadService())->saveFile($this->attributes['image'], 'subscribe');

            $this->attributes['image'] = $file['name'];
        }

        $subscribe = new SubscribeSplash($this->attributes);

        $subscribe->save();

        return $subscribe;
    }
}
