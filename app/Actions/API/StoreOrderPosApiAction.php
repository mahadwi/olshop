<?php

namespace App\Actions\API;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Mail\InvoiceMail;
use Illuminate\Support\Str;
use App\Constants\OrderState;
use App\Constants\PaymentState;
use Illuminate\Support\Facades\DB;
use App\Actions\StorePaymentAction;
use App\Services\Order\OrderService;
use Illuminate\Support\Facades\Mail;
use App\Services\Xendit\XenditService;


class StoreOrderPosApiAction
{
    private $attributes;
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        return DB::transaction(function () {

            //order
            $order = new Order($this->attributes);

            $order->status = $this->attributes['is_offline'] ? OrderState::COMPLETED : OrderState::UNPAID;

            $order->save();

            //order detail
            foreach($this->attributes['details'] as $detail){

                $product = Product::find($detail['product_id']);

                $product->stock = $product->stock - $detail['qty'];
                $product->save();


                dispatch_sync(new StoreOrderDetailAction($detail, $order));
                
            }

            $paramPayment = [
                'status'    => PaymentState::PAID,
                'paid_at'   => date('Y-m-d h:i:s')
            ];

            if(!$this->attributes['is_offline']){
                $external_id = (string) Str::uuid();
                //create invoice
                $params = [
                    'external_id'           => $external_id,
                    'description'           => 'Order POS Payment',
                    'amount'                => $this->attributes['total'],
                    'success_redirect_url'  => config('app.default.xendit_success_url'),                    
                ];

                $invoice = (new XenditService)->createInvoice($params);                            

                $paramPayment = [
                    'external_id' => $external_id,
                    'invoice_url' => $invoice['invoice_url'], 
                    'expired'     => Carbon::parse($invoice['expiry_date'])->setTimezone('Asia/Jakarta')
                ];
            }

            //store payment
            dispatch_sync(new StorePaymentAction($paramPayment, $order));

            // jika cash maka langsung kirim email
            if($this->attributes['is_offline']){
                //generate invoice
                (new OrderService)->generateInvoice($order);
    
                Mail::to($order->user->email)->send(new InvoiceMail($order));
            }            

            return $order;
        });
    }
}