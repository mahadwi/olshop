<?php

namespace App\Transformers\API;

use App\Models\Authentication;
use League\Fractal\TransformerAbstract;

class AuthenticationTransformer extends TransformerAbstract
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
        'authentication_sections'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Authentication $authentication)
    {
        return [
            "id" => $authentication->id,
            "title" => $authentication->title,
            "title_en" => $authentication->title_en,
            "description" => $authentication->description,
            "description_en" => $authentication->description_en,
            "phone" => $authentication->no_hp,
            "link" => $authentication->link,
        ];
    }

    public function includeAuthenticationSections($authentication)
    {
        return $this->collection($authentication->authenticationDetail, new AuthenticationDetailTransformer);
    }
}
