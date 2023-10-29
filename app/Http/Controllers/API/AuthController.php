<?php

namespace App\Http\Controllers\API;

use App\Actions\API\RegisterCustomerAction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\AuthenticateRequest;
use App\Http\Requests\API\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return $this->apiSuccess([],[], 'logout Sukses');
    }
}
