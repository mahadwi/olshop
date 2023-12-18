<?php

namespace App\Actions;

use App\Models\Faq;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;

class StoreFaqAction
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
        $faq = new Faq($this->attributes);
        $faq->save();

        return $faq;
    }
}
