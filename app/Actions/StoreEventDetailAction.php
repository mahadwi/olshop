<?php

namespace App\Actions;

use App\Models\Event;
use App\Models\Gallery;
use App\Models\EventDetail;
use Illuminate\Support\Facades\DB;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;

class StoreEventDetailAction
{
    private $attributes;
    private $event;
    
    public function __construct(Event $event, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->event = $event;
    }

    public function handle()
    {
        return DB::transaction(function () {

            $eventDetail = new EventDetail($this->attributes); 
            $eventDetail->event_id = $this->event->id;           
            $eventDetail->save();

            return $eventDetail;
        });
       
    }
}
