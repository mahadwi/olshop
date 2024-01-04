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
        
        $ip = $this->getUserIpAddr();

        $location = Http::get($this->url.'?ip='.$ip)->json();

        return $this->apiSuccess($location);

    }

    private function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
