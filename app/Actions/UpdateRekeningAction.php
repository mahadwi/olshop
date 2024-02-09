<?php

namespace App\Actions;

use App\Models\Rekening;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;

class UpdateRekeningAction
{
    private $rekening;
    private $attributes;


    public function __construct(Rekening $rekening, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->rekening = $rekening;
    }

    public function handle()
    {   
        
        if($this->attributes['logo']){
            $oldFile = public_path('image/'.$this->rekening->logo);

            if(File::exists($oldFile)){       
                //delete file
                File::delete($oldFile);
            }

            $file = (new UploadService())->saveFile($this->attributes['logo']);  

            $this->attributes['logo'] = $file['name'];

        } else {
            unset($this->attributes['logo']);
        }

        $this->rekening->fill($this->attributes)->save();

        return $this->rekening;
    }
}