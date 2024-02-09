<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Rekening;
use Illuminate\Http\Request;

class RekeningApiController extends Controller
{
    public function index()
    {
        $rekening = Rekening::select('bank', 'bank_account_holder', 'bank_account_number', 'logo')->get();

        return $this->apiSuccess($rekening);
    }
}
