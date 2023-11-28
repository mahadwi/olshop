<?php

namespace App\Actions;

use App\Models\Event;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\File\File;

class StoreEventAction
{
    private $attributes;
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    private function isImage(object $file)
    {
        return $file instanceof File;
    }

    public function handle()
    {
        return DB::transaction(function () {
            
            if($this->isImage($this->attributes['cover'])  && $this->isImage($this->attributes['banner']) ){
                
                $cover = (new UploadService())->saveFile($this->attributes['cover'], 'event');
                $banner = (new UploadService())->saveFile($this->attributes['banner'], 'event');
    
                $this->attributes['cover'] = $cover['name'];
                $this->attributes['banner'] = $banner['name'];
            }

            $event = new Event($this->attributes);

            $event->created_by = Auth::id();
            $event->save();

            foreach($this->attributes['details'] as $detail){
                (new StoreEventDetailAction($event, $detail))->handle();
            }

            return $event;
        });
        
    }
}
