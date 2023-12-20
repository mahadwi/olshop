<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherApiController extends Controller
{
    public function show($voucher)
    {    
        $voucher = Voucher::where('code', $voucher)->first();
        
        if(!$voucher){
            return $this->apiError([], [], 'Voucher Not Found');
        }

        return $this->apiSuccess($voucher);
    }
    
}
