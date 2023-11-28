<?php

namespace App\Actions;

use App\Models\Event;
use Illuminate\Support\Facades\DB;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;


class UpdateEventAction
{
    private $event;
    private $attributes;


    public function __construct(Event $event, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->event = $event;
    }

    public function handle()
    {   
        return DB::transaction(function () {

            $this->cekImage();

            $this->event->fill($this->attributes)->save();

            $this->event->details()->delete();

            foreach($this->attributes['details'] as $detail){
                (new StoreEventDetailAction($this->event, $detail))->handle();
            }

            return $this->event;
        });
    }

    public function cekImage()
    {
        $images = ['banner', 'cover'];

        foreach($images as $image){
            if($this->attributes[$image]){
                $oldFile = public_path('image/event/'.$this->event->$image);

                if(File::exists($oldFile)){       
                    //delete file
                    File::delete($oldFile);
                }

                $file = (new UploadService())->saveFile($this->attributes[$image], 'event');  

                $this->attributes[$image] = $file['name'];
            } else {
                unset($this->attributes[$image]);
            }
        }
    }
}
