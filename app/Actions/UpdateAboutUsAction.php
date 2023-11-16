<?php

namespace App\Actions;

use App\Models\AboutUs;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;


class UpdateAboutUsAction
{
    private $about;
    private $attributes;


    public function __construct(AboutUs $about, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->about = $about;
    }

    public function handle()
    {   
        if($this->attributes['image']){

            $oldFile = public_path('image/'.$this->about->image);

            if(File::exists($oldFile)){       
                //delete file
                File::delete($oldFile);
            }

            $file = (new UploadService())->saveFile($this->attributes['image']);  

            $this->attributes['image'] = $file['name'];

        } else {
            unset($this->attributes['image']);
        }

        $this->about->fill($this->attributes)->save();

        return $this->about;
    }
}
