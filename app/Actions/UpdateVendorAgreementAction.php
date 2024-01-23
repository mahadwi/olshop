<?php

namespace App\Actions;

use App\Models\VendorAgreement;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;

class UpdateVendorAgreementAction
{
    private $agreement;
    private $attributes;

    public function __construct(VendorAgreement $agreement, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->agreement = $agreement;
    }

    public function handle()
    {   
        if(isset($this->attributes['file'])){

            $oldFile = public_path('file/'.$this->agreement->file);

            if(File::exists($oldFile)){       
                //delete file
                File::delete($oldFile);
            }

            $file = (new UploadService())->uploadFile($this->attributes['file']);  

            $this->attributes['file'] = $file['name'];

        } else {
            unset($this->attributes['file']);
        }

        $this->agreement->fill($this->attributes)->save();

        return $this->agreement;
    }
}