<?php

namespace App\Actions;

use App\Models\User;

class UpdateUserAction
{
    private $user;
    private $attributes;

    public function __construct(User $user, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->user = $user;
    }

    public function handle()
    {
        return $this->user->fill($this->attributes)->save();
    }
}
