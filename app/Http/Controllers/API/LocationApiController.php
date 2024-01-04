<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class LocationApiController extends Controller
{

    private $url;
    
    public function __construct()
    {
        $this->url = 'https://api.iplocation.net';    
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $ip = $request->getClientIp();
        // $ip = '114.10.98.157';

        $location = Http::get($this->url.'?ip='.$ip)->json();

        return $this->apiSuccess($location);

    }
}
