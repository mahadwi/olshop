<?php

namespace App\Actions\API;

use App\Models\Afiliator;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DeleteOtpAction
{
    public function handle(User $user)
    {
        return DB::transaction(function () use ($user) {
            $user->otp = null;
            $user->otp_expired_at = null;
            $user->otp_next_try = null;
            $user->save();

            return $user;
        });
    }
}
