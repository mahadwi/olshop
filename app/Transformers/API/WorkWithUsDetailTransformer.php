<?php

namespace App\Transformers\API;

use App\Models\WorkWithUsDetail;
use League\Fractal\TransformerAbstract;

class WorkWithUsDetailTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'card'
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
    public function transform(WorkWithUsDetail $workWithUsDetail)
    {
        // dd($workWithUsDetail);
        return [
            'id'                => $workWithUsDetail->id,
            'work_with_us_id'   => $workWithUsDetail->work_with_us_id,
            'section'           => $workWithUsDetail->section,
            "title"             => $workWithUsDetail->title,
            "title_en"          => $workWithUsDetail->title_en,
            "description"       => $workWithUsDetail->description,
            "description_en"    => $workWithUsDetail->description_en,
            "image"             => $workWithUsDetail->image ? $workWithUsDetail->image_url : '',
            "link"              => $workWithUsDetail->link,
        ];
    }

    public function includeCard($workWithUsDetail)
    {
        return $this->collection($workWithUsDetail->workWithUsCard, new WorkWithUsCardTransformer);
    }
}
