<?php

namespace App\Transformers\API;

use App\Models\AuthenticationDetail;
use League\Fractal\TransformerAbstract;

class AuthenticationDetailTransformer extends TransformerAbstract
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
    public function transform(AuthenticationDetail $authenticationDetail)
    {
        return [
            'id'                => $authenticationDetail->id,
            'authentication_id' => $authenticationDetail->authentication_id,
            'section'           => $authenticationDetail->section,
            'title'             => $authenticationDetail->title,
            'title_en'          => $authenticationDetail->title_en,
            'description'       => $authenticationDetail->description,
            'description_en'    => $authenticationDetail->description_en,
            'image'             => $authenticationDetail->image_url,
        ];
    }

}
