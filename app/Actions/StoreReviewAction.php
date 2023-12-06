<?php

namespace App\Actions;

use App\Models\Review;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;

class StoreReviewAction
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

        $review = new Review($this->attributes);
        
        $review->save();

        return $review;
    }
}
