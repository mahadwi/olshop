<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Email;
use App\Constants\Role;
use Illuminate\Http\Request;
use App\Actions\API\StoreOtpAction;
use App\Actions\API\DeleteOtpAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Actions\API\UpdateUserApiAction;
use Laravel\Socialite\Facades\Socialite;
use App\Transformers\API\UserTransformer;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\API\CheckOtpRequest;
use App\Http\Requests\API\RegisterRequest;
use App\Actions\API\RegisterCustomerAction;
use App\Http\Requests\API\AuthenticateRequest;
use App\Notifications\CustomerOtpNotification;
use App\Http\Requests\API\RequestOtpApiRequest;
use App\Http\Requests\API\UpdateUserApiRequest;
use App\Http\Requests\API\ChangePasswordRequest;

class AuthController extends Controller
{

    public function login(AuthenticateRequest $request)
    {

        $user = $this->validateUser($request->validated());

        if (!($user instanceof User)) {
            return $this->apiError([], [], $user);
        }

        if (Auth::attempt($request->validated(), false)) {
            return $this->apiSuccess([
                'user' => fractal($user, new UserTransformer),
                'token' => $user->createToken('auth_token')->plainTextToken,
            ]);
        }

        return $this->apiError([], [], 'Invalid login credentials.');

    }

    public function user(Request $request)
    {
        $user = $request->user();
        $email = $user->email;

        $emailExists = Email::where('email', $email)->exists();
        $emailExists ? $user->update(['is_subscribe' => true]) : $user->update(['is_subscribe' => false]);
        
        return $this->apiSuccess(fractal($user, new UserTransformer)->parseIncludes(['addresses']));
    }

    public function requestOtp(RequestOtpApiRequest $request, StoreOtpAction $storeOtpAction)
    {
        //check if user exist
        $user = $this->validateUser($request->validated());

        if (!($user instanceof User)) {
            return $this->apiError([], [], $user);
        }

        $storeOtpAction->handle($user);
        $user->notify(new CustomerOtpNotification($user));
        
        return $this->apiSuccess([], 'Success Request OTP');
        
    }

    public function checkOtp(CheckOtpRequest $request, DeleteOtpAction $deleteOtpAction)
    {
        $user = $this->validateUser($request->validated());

        if (!($user instanceof User)) {
            return $this->apiError([], [], $user);
        }

        if ($user->otp !== $request->otp || Carbon::now()->greaterThan($user->otp_expired_at)) {
            return $this->apiError([], [], 'invalid otp');
        }

        $deleteOtpAction->handle($user);

        return $this->apiSuccess([], 'Success');

    }

    public function updateUser(UpdateUserApiRequest $request)
    {
        $userId = $request->user()->id; // Mengambil user id dari sesi
        $requestData = array_merge($request->all(), ['user_id' => $userId]);
        $users = dispatch_sync(new UpdateUserApiAction($requestData));

        $user = fractal($users, new UserTransformer())->toArray();
        return $this->apiSuccess($user);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $this->validateUser($request->validated());

        if (!($user instanceof User)) {
            return $this->apiError([], [], $user);
        }
        
        $user->password = bcrypt($request->password);

        $user->save();

        return $this->apiSuccess([], [], 'Success Update Password');

    }

    public function redirectToProvider(Request $request)
    {
        $validated = $this->validateProvider($request);
        if (!is_null($validated)) {
            return $validated;
        }
        
        return response()->json([
            'url' => Socialite::driver($request->provider)->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    public function handleAuthCallback(Request $request)
    {
        $validated = $this->validateProvider($request);
        if (!is_null($validated)) {
            return $validated;
        }

        try {
            $user = Socialite::driver($request->provider)->stateless()->user();
        } catch (ClientException $exception) {
            return $this->apiError([], [], 'Invalid credentials provided.', 422);
        }

        $userCreated = User::firstOrCreate(
            [
                'email' => $user->getEmail()
            ],
            [
                'email_verified_at' => Carbon::now(),
                'name' => $user->getName(),
                'google_id' => $user->getId(),
                'password'  => bcrypt('password')
            ]
        );

        $userCreated->assignRole(Role::CUSTOMER);

        $token = $userCreated->createToken('auth_token')->plainTextToken;

        return $this->apiSuccess([
            'user' => $user,
            'token' => $token,
        ],[]);
    }


    public function logout()
    {
        Auth::user()->tokens()->delete();

        return $this->apiSuccess();
    }

    protected function validateProvider($request)
    {
        if (!in_array($request->provider, ['facebook', 'google'])) {
            return $this->apiError([], [], 'Please login using facebook or google.', 422);
        }
    }

    private function validateUser($request)
    {
        $user = User::where('email', $request['email'])->first();

        if (is_null($user)) {
            return 'email not registered';
        }

        if (!$user->hasRole([Role::CUSTOMER])) {
            return 'something wrong';
        }

        if (is_null($user->email_verified_at)) {
            return 'unverified account';
        }

        return $user;
    }
}
