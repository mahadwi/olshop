<?php

namespace App\Actions;

use App\Models\Authentication;

class StoreAuthenticationAction
{
    private $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        $authentication = new Authentication($this->attributes);
        $authentication->save();

        return $authentication;
    }
}
