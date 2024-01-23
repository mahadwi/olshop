<?php

namespace App\Actions;

use App\Models\Profile;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;

class UpdateProfileAction
{
    private $profile;
    private $attributes;

    public function __construct(Profile $profile, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->profile = $profile;
    }

    public function handle()
    {   
        if($this->attributes['logo']){

            $oldFile = public_path('image/'.$this->profile->logo);

            if(File::exists($oldFile)){       
                //delete file
                File::delete($oldFile);
            }

            $file = (new UploadService())->saveFile($this->attributes['logo']);  

            $this->attributes['logo'] = $file['name'];

        } else {
            unset($this->attributes['logo']);
        }

        $this->attributes['subdistrict_id'] = $this->attributes['subdistrict_id']['value'];

        $this->profile->fill($this->attributes)->save();

        return $this->profile;
    }
}