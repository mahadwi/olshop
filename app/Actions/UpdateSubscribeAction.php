<?php

namespace App\Actions;

use App\Models\SubscribeSplash;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;


class UpdateSubscribeAction
{
    private $subscribe;
    private $attributes;


    public function __construct(SubscribeSplash $subscribe, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->subscribe = $subscribe;
    }

    public function handle()
    {

        if($this->attributes['image']){
            $oldFile = public_path('image/subscribe/'.$this->subscribe->image);

            if(File::exists($oldFile)){
                //delete file
                File::delete($oldFile);
            }

            $file = (new UploadService())->saveFile($this->attributes['image'], 'subscribe');

            $this->attributes['image'] = $file['name'];

        } else {
            unset($this->attributes['image']);
        }

        $this->subscribe->fill($this->attributes)->save();

        return $this->subscribe;
    }
}
