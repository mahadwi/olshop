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
            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->otp = $otp;
            $user->otp_expired_at = Carbon::now()->addSeconds(config('app.default.otp_lifetime'));
            $user->otp_next_try = Carbon::now()->addSeconds(config('app.default.otp_next_try'));
            $user->save();

            return $user;
        });
    }
}
