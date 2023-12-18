<?php

namespace App\Actions;

use App\Models\Faq;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;


class UpdateFaqAction
{
    private $faq;
    private $attributes;


    public function __construct(Faq $faq, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->faq = $faq;
    }

    public function handle()
    {
        if($this->attributes['image']){

            $oldFile = public_path('image/'.$this->faq->image);

            if(File::exists($oldFile)){
                //delete file
                File::delete($oldFile);
            }

            $file = (new UploadService())->saveFile($this->attributes['image']);

            $this->attributes['image'] = $file['name'];

        } else {
            unset($this->attributes['image']);
        }

        $this->faq->fill($this->attributes)->save();

        return $this->faq;
    }
}
