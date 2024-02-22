<?php

namespace App\Transformers\API;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
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
        'addresses'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            "id" => $user->id,
            "name" => $user->name,
            "phone" => $user->no_hp,
            "email" => $user->email,
            "userName" => $user->user_name,
            "gender" => $user->gender,
            "birthDate" => $user->birth_date,
            // "role" => $user->roles[0]->name,
            "image" => $user->image,
            "is_subscribe" => $user->is_subscribe
        ];
    }

    public function includeAddresses($user)
    {
        return $this->collection($user->addresses, new AddressTransformer);
    }
}
