<?php

namespace App\Services\Xendit;

use Exception;
use Carbon\Carbon;
use Xendit\Xendit;
use Xendit\Invoice;
use App\Models\Booking;
use App\Models\Payment;
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
    
        if($payment->status == PaymentState::PAID){
            throw new Exception('Already Paid', 422);
        }

        $params = [
            'status' => ucfirst(strtolower($data['status'])),            
        ];        

        $webhook = dispatch_sync(new UpdatePaymentAction($payment, $params));

        return $webhook;
    }

}
