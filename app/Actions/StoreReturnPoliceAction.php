<?php

namespace App\Actions;

use App\Models\ReturnPolice;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;

class StoreReturnPoliceAction
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

        $returnPolice = new ReturnPolice();
        $returnPolice->title = $this->attributes['title'];
        // $returnPolice->description = json_encode($this->attributes['description']);
        $returnPolice->description = $this->attributes['description'];
        $returnPolice->cp = $this->attributes['cp'];
        $returnPolice->image = $this->attributes['image'];

        $returnPolice->save();

        return $returnPolice;
    }
}
