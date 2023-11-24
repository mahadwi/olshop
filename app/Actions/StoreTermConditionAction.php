<?php

namespace App\Actions;

use App\Models\TermCondition;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;

class StoreTermConditionAction
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

        $termCondition = new TermCondition($this->attributes);

        $termCondition->save();

        return $termCondition;
    }
}
