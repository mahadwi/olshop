<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Actions\API\StoreOtpAction;
use App\Actions\API\DeleteOtpAction;
use App\Http\Controllers\Controller;
use App\Transformers\API\UserTransformer;
use App\Http\Requests\API\RegisterRequest;
use App\Actions\API\RegisterCustomerAction;
use App\Http\Requests\API\VerifyEmailRequest;
use App\Notifications\CustomerOtpNotification;
use App\Http\Requests\API\RequestVerifyEmailRequest;

class RegisterApiController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::where('email', $request['email'])->first();

        if ($user && is_null($user->email_verified_at)) {
            return $this->apiError([], [], 'unverified account');
        }

        $user = (new RegisterCustomerAction($request->all()))->handle();        
        $userOtp = (new StoreOtpAction)->handle($user);

        $userOtp->notify(new CustomerOtpNotification($userOtp));

        $userFormatted = fractal($user, new UserTransformer);
        return $this->apiSuccess($userFormatted);
    }

    public function verifyEmail(VerifyEmailRequest $request, DeleteOtpAction $deleteOtpAction)
    {
        $user = User::where([['email', $request->email], ['otp', $request->otp]])->first();

        if (is_null($user)) {
            return $this->apiError([], [], 'email verify failed');
        }

        $user->email_verified_at = Carbon::now();
        $deleteOtpAction->handle($user);

        return $this->apiSuccess();
    }

    public function requestVerifyEmail(RequestVerifyEmailRequest $request, StoreOtpAction $storeOtpAction)
    {
        $user = User::where('email', $request->email)->first();

        if (is_null($user)) {
            return $this->apiError([], 'something wrong');
        }

        $storeOtpAction->handle($user);
        $user->notify(new CustomerOtpNotification($user));

        return $this->apiSuccess();
    }
}
