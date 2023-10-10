<?php

namespace App\Actions\API;

use Carbon\Carbon;
use App\Models\User;
use App\Constants\Role;
use Illuminate\Support\Facades\DB;

class RegisterCustomerAction
{
    private $attributes;
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        
        return DB::transaction(function () {

            $user = new User;
            $user->fill($this->attributes);           
            $user->save();

            $user->assignRole(Role::CUSTOMER);

            return $user;
        });
    }
}