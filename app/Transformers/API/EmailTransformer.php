<?php

namespace App\Transformers\API;

use App\Models\Email;
use League\Fractal\TransformerAbstract;

class EmailTransformer extends TransformerAbstract
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
    public function transform(Email $email)
    {
        return [
            'id'            => $email->id,
            'name'          => $email->name,
            'email'         => $email->email,
            'handphone'     => $email->handphone,
        ];
    }

}
