<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Constants\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Transformers\API\UserTransformer;
use App\Http\Requests\API\AuthenticateRequest;

class LoginPosController extends Controller
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

    private function validateUser($request)
    {
        $user = User::where('email', $request['email'])->first();

        if (is_null($user)) {
            return 'email not registered';
        }

        if (!$user->hasRole([Role::KASIR])) {
            return 'Role user not valid';
        }


        return $user;
    }
}
