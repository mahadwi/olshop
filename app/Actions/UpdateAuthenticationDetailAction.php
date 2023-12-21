<?php

namespace App\Actions;

use App\Models\AuthenticationDetail;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;


class UpdateAuthenticationDetailAction
{
    private $authenticationDetail;
    private $attributes;


    public function __construct(AuthenticationDetail $authenticationDetail, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->authenticationDetail = $authenticationDetail;
    }

    public function handle()
    {
        if($this->attributes['image']){

            $oldFile = public_path('image/'.$this->authenticationDetail->image);

            if(File::exists($oldFile)){
                //delete file
                File::delete($oldFile);
            }

            $file = (new UploadService())->saveFile($this->attributes['image']);

            $this->attributes['image'] = $file['name'];

        } else {
            unset($this->attributes['image']);
        }

        $this->authenticationDetail->fill($this->attributes)->save();

        return $this->authenticationDetail;
    }
}
