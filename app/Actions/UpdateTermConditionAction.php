<?php

namespace App\Actions;

use App\Models\TermCondition;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;


class UpdateTermConditionAction
{
    private $termCondition;
    private $attributes;


    public function __construct(TermCondition $termCondition, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->termCondition = $termCondition;
    }

    public function handle()
    {
        if($this->attributes['image']){

            $oldFile = public_path('image/'.$this->termCondition->image);

            if(File::exists($oldFile)){
                //delete file
                File::delete($oldFile);
            }

            $file = (new UploadService())->saveFile($this->attributes['image']);

            $this->attributes['image'] = $file['name'];

        } else {
            unset($this->attributes['image']);
        }

        $this->termCondition->fill($this->attributes)->save();

        return $this->termCondition;
    }
}
