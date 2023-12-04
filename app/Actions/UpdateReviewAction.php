<?php

namespace App\Actions;

use App\Models\Review;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;


class UpdateReviewAction
{
    private $review;
    private $attributes;


    public function __construct(Review $review, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->review = $review;
    }

    public function handle()
    {   
        if($this->attributes['image']){

            $oldFile = public_path('image/'.$this->review->image);

            if(File::exists($oldFile)){       
                //delete file
                File::delete($oldFile);
            }

            $file = (new UploadService())->saveFile($this->attributes['image']);  

            $this->attributes['image'] = $file['name'];

        } else {
            unset($this->attributes['image']);
        }

        $this->review->fill($this->attributes)->save();

        return $this->review;
    }
}
