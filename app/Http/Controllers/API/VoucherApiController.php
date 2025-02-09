<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\VoucherApiRequest;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherApiController extends Controller
{
    public function index(VoucherApiRequest $request)
    {    
        $today = date('Y-m-d');

        $voucher = Voucher::where('use_for', $request->use_for)
        ->whereDate('start_date', '<=', $today)
        ->whereDate('end_date', '>=', $today)
        ->get();
        
        return $this->apiSuccess($voucher);
    }

    public function show($voucher)
    {    
        $voucher = Voucher::where('code', $voucher)->first();
        
        if(!$voucher){
            return $this->apiError([], [], 'Voucher Not Found');
        }

        return $this->apiSuccess($voucher);
    }
    
}
