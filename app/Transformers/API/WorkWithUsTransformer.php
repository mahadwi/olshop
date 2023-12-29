<?php

namespace App\Transformers\API;

use App\Models\WorkWithUs;
use League\Fractal\TransformerAbstract;

class WorkWithUsTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'sections'
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        // 'sections'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(WorkWithUs $workWithUs)
    {
        return [
            "id" => $workWithUs->id,
            "title" => $workWithUs->title,
            "title_en" => $workWithUs->title_en,
            "description" => $workWithUs->description,
            "description_en" => $workWithUs->description_en,
        ];
    }

    public function includeSections($workWithUs)
    {
        return $this->collection($workWithUs->workWithUsDetail, new WorkWithUsDetailTransformer);
    }
}
