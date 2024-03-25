<?php

namespace App\Actions\API;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Constants\OrderState;
use Illuminate\Support\Facades\DB;
use App\Actions\StorePaymentAction;
use App\Constants\PaymentState;
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

            $order->status = $this->attributes['is_cash'] ? OrderState::COMPLETED : OrderState::UNPAID;

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

            if(!$this->attributes['is_cash']){
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

            // //store payment
            dispatch_sync(new StorePaymentAction($paramPayment, $order));

            return $order;
        });
    }
}