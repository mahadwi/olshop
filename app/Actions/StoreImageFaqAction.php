<?php

namespace App\Actions;

use App\Models\FaqImage;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;

class StoreImageFaqAction
{
    private $attributes;

    public function __construct($faqImage, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->faqImage = $faqImage;
    }

    public function handle()
    {
        if($this->attributes['image']){

            $oldFile = public_path('image/faq/'.$this->faqImage->image);

            if(File::exists($oldFile)){
                //delete file
                File::delete($oldFile);
            }

            $file = (new UploadService())->saveFile($this->attributes['image'], 'faq');

            $this->attributes['image'] = $file['name'];

        } else {
            unset($this->attributes['image']);
        }

        $this->faqImage->fill($this->attributes)->save();

        return $this->faqImage;
    }
}
