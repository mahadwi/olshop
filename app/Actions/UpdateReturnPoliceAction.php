<?php

namespace App\Actions;

use App\Models\ReturnPolice;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;


class UpdateReturnPoliceAction
{
    private $returnPolice;
    private $attributes;


    public function __construct(ReturnPolice $returnPolice, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->returnPolice = $returnPolice;
    }

    public function handle()
    {
        if($this->attributes['image']){

            $oldFile = public_path('image/'.$this->returnPolice->image);

            if(File::exists($oldFile)){
                //delete file
                File::delete($oldFile);
            }

            $file = (new UploadService())->saveFile($this->attributes['image']);

            $this->attributes['image'] = $file['name'];

        } else {
            unset($this->attributes['image']);
        }

        $this->returnPolice->fill($this->attributes)->save();

        return $this->returnPolice;
    }
}
