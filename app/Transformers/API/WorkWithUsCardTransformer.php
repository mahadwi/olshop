<?php

namespace App\Transformers\API;

use App\Models\WorkWithUsCard;
use League\Fractal\TransformerAbstract;

class WorkWithUsCardTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [

    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(WorkWithUsCard $workWithUsCard)
    {
        return [
            'id'                => $workWithUsCard->id,
            'work_with_us_detail_id'           => $workWithUsCard->work_with_us_detail_id,
            "title"             => $workWithUsCard->title,
            "title_en"          => $workWithUsCard->title_en,
            "description"       => $workWithUsCard->description,
            "description_en"    => $workWithUsCard->description_en,
            "image"             => $workWithUsCard->icon ? $workWithUsCard->icon_url : '',
            "card"              => $workWithUsCard->card
        ];
    }
}
