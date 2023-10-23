<?php

namespace App\Http\Controllers\Api;

use App\Actions\API\RegisterCustomerAction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required|string|min:8',
            // 'no_hp'     => 'required|min:11'
        ]);

        if ($validator->fails()) {
            return $this->apiError([$validator->errors()], [], 'something wrong');
        }

        $user = (new RegisterCustomerAction($request->all()))->handle();        

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->apiSuccess([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        if (! Auth::attempt($request->only('email', 'password'))) {

            return $this->apiError([], [], 'Unauthorized', 401);
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
