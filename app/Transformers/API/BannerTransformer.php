<?php

namespace App\Transformers\API;

use App\Models\Banner;
use League\Fractal\TransformerAbstract;

class BannerTransformer extends TransformerAbstract
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
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Banner $banner)
    {
        return [
            'section'       => $banner->section,
            'title'         => $banner->title,
            'description'   => $banner->description,
            'title_en'         => $banner->title_en,
            'description_en'   => $banner->description_en,
            'images'        => $this->images($banner)
        ];
    }

    private function images($banner)
    {
        return is_null($banner->imageable) ? [] : $banner->imageable->map(function ($item) {
            return asset('image/'.$item->name);
        });
    }
}
