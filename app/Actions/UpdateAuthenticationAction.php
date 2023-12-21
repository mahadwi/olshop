<?php

namespace App\Actions;

use App\Models\Authentication;

class UpdateAuthenticationAction
{
    private $authentication;
    private $attributes;


    public function __construct(Authentication $authentication, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->authentication = $authentication;
    }

    public function handle()
    {
        $this->authentication->fill($this->attributes)->save();

        return $this->authentication;
    }
}
