<?php

namespace App\Services\Xendit;

use Exception;
use Carbon\Carbon;
use Xendit\Xendit;
use Xendit\Invoice;
use App\Models\Order;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\BankCode;
use App\Mail\InvoiceMail;
use Xendit\VirtualAccounts;
use App\Constants\PaymentState;
use App\Actions\UpdatePaymentAction;
use App\Constants\OrderState;
use App\Services\Order\OrderService;
use Illuminate\Support\Facades\Mail;

class XenditService
{

    public function createInvoice($params)
    {
        Xendit::setApiKey(config('app.default.xendit_secret_key'));
        
        $params['invoice_duration'] = 86400;

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

        self::sendMail($payment);
        self::updateStatus($payment->paymentable);

        $webhook = dispatch_sync(new UpdatePaymentAction($payment, $params));
        
        return $webhook;
    }

    public static function sendMail($payment)
    {
        if($payment->paymentable instanceof Order && $payment->paymentable->is_pos){

            $order = $payment->paymentable;

            (new OrderService)->generateInvoice($order);
    
            Mail::to($order->user->email)->send(new InvoiceMail($order));

        }
    }

    public static function updateStatus($order)
    {
        if($order instanceof Order && !$order->is_pos && $order->ongkir != 'pickup'){

            $order->status = OrderState::ONPROCESS;

            $order->save();

        } else if($order instanceof Order && ($order->ongkir == 'pickup' || $order->is_pos)){

            $order->status = OrderState::COMPLETED;

            $order->save();
        }
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
