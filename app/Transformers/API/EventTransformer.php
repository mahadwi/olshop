<?php

namespace App\Transformers\API;

use App\Models\Event;
use League\Fractal\TransformerAbstract;

class EventTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        'details'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Event $event)
    {
        return [
            'id'            => $event->id,
            'name'          => $event->name,
            'description'   => $event->description,
            'description_en'=> $event->description_en,
            'start_date'    => $event->start_date->format('d-m-Y'),
            'end_date'      => $event->end_date->format('d-m-Y'),
            'time_start'    => $event->time_start,
            'time_end'      => $event->time_end,
            'place'         => $event->place,
            'maps'          => $event->maps,
            'detail_maps'   => $event->detail_maps,
            'cover_image'   => $event->cover_url,
            'banner_image'  => $event->banner_url
            //
        ];
    }

    public function includeDetails($event)
    {
        return $this->collection($event->details, new EventDetailTransformer);
    }
}
