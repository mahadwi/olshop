<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Authentication;
use App\Models\AuthenticationDetail;
use Illuminate\Http\Request;
use App\Transformers\API\AuthenticationTransformer;

class AuthenticationApiController extends Controller
{
    public function index()
    {
        $data = Authentication::with('authenticationDetail')->get();
        $authentication = fractal($data, new AuthenticationTransformer)->parseIncludes(['authentication_sections'])->toArray();
        return $this->apiSuccess($authentication);
    }
}
