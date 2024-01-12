<?php

namespace App\Http\Controllers\API;

use Exception;
use Xendit\Xendit;
use App\Models\BankCode;
use Xendit\VirtualAccounts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Xendit\XenditService;

class BankCodeController extends Controller
{
    public function __invoke()
    {
        try {
            
            $data = BankCode::select('code', 'name')->get();

            if($data->isEmpty()){
                $data = (new XenditService)->getBankCode();
            }

            return $this->apiSuccess($data);

        } catch (Exception $e) {
            return $this->apiError($e->getMessage());
        }
    }
}
