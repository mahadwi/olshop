<?php

namespace App\Actions\API;

use App\Models\Afiliator;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StoreOtpAction
{
    public function handle(User $user)
    {
        return DB::transaction(function () use ($user) {
            $otp = rand(000000, 999999);
            $user->otp = $otp;
            $user->otp_expired_at = Carbon::now()->addSeconds(config('app.default.otp_lifetime'));
            $user->otp_next_try = Carbon::now()->addSeconds(config('app.default.otp_next_try'));
            $user->save();

            return $user;
        });
    }
}
