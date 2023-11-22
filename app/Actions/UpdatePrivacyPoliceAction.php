<?php

namespace App\Actions;

use App\Models\PrivacyPolice;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;


class UpdatePrivacyPoliceAction
{
    private $privacyPolice;
    private $attributes;


    public function __construct(PrivacyPolice $privacyPolice, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->privacyPolice = $privacyPolice;
    }

    public function handle()
    {
        if($this->attributes['image']){

            $oldFile = public_path('image/'.$this->privacyPolice->image);

            if(File::exists($oldFile)){
                //delete file
                File::delete($oldFile);
            }

            $file = (new UploadService())->saveFile($this->attributes['image']);

            $this->attributes['image'] = $file['name'];

        } else {
            unset($this->attributes['image']);
        }

        $this->privacyPolice->fill($this->attributes)->save();

        return $this->privacyPolice;
    }
}
