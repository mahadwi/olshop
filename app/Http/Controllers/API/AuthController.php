<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Constants\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\API\RegisterRequest;
use App\Actions\API\RegisterCustomerAction;
use App\Http\Requests\API\AuthenticateRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = (new RegisterCustomerAction($request->all()))->handle();        

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->apiSuccess([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function login(AuthenticateRequest $request)
    {

        if (! Auth::attempt($request->only('email', 'password'))) {

            return $this->apiError([], [], 'The provided credentials do not match our records.', 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->apiSuccess([
            'user' => $user,
            'token' => $token,
        ],[], 'Login Sukses');
        
    }

    public function redirectToProvider($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $exception) {
            return $this->apiError([], [], 'Invalid credentials provided.', 422);
        }

        $userCreated = User::firstOrCreate(
            [
                'email' => $user->getEmail()
            ],
            [
                'email_verified_at' => now(),
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

        return $this->apiSuccess([],[], 'logout Sukses');
    }

    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'google'])) {
            return $this->apiError([], [], 'Please login using facebook or google.', 422);
        }
    }
}
