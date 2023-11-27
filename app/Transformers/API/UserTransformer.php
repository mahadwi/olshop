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
            "role" => $user->roles[0]->name,
        ];
    }

    public function includeAddresses($user)
    {
        return $this->item($user->addresses, new AddressTransformer);
    }
}
