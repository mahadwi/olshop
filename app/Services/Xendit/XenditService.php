<?php

namespace App\Services\Xendit;

use Exception;
use Carbon\Carbon;
use Xendit\Xendit;
use Xendit\Invoice;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\BankCode;
use Xendit\VirtualAccounts;
use App\Constants\PaymentState;
use App\Actions\UpdatePaymentAction;

class XenditService
{

    public function createInvoice($params)
    {
        Xendit::setApiKey(config('app.default.xendit_secret_key'));
        
        $invoice = \Xendit\Invoice::create($params);

        return $invoice;
    }

    public static function callbackPaid($data)
    {
        $payment = Payment::where('external_id', $data['external_id'])->first();
        if (!$payment) {
            throw new Exception('Payment not found', 422);
        }
    
        if($payment->status == PaymentState::PAID && $payment->payment_method != null){
            throw new Exception('Already Paid', 422);
        }

        $params = [
            'status'          => ucfirst(strtolower($data['status'])),      
            'payment_method'  => $data['payment_method'],     
            'payment_channel' => $data['payment_channel'],  
            'paid_at'         => Carbon::parse($data['paid_at'])->setTimezone('Asia/Jakarta')
        ];        

        $webhook = dispatch_sync(new UpdatePaymentAction($payment, $params));

        return $webhook;
    }

    public static function getBankCode()
    {
        Xendit::setApiKey(config('app.default.xendit_secret_key'));

        $banks = VirtualAccounts::getVABanks();

        $filter = collect($banks)->filter(function($filter){
            return $filter['country'] == 'ID' AND $filter['currency'] == 'IDR';
        });

        $filter->each(function($bank){
            $insert = new BankCode();
            $insert->code = $bank['code'];
            $insert->name = $bank['name'];
            $insert->save();                                
        });

        $data = BankCode::select('code', 'name')->get();

        return $data;
    }

}
