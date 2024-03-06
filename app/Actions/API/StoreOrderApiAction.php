<?php

namespace App\Actions\API;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Actions\StorePaymentAction;
use App\Services\Xendit\XenditService;
use App\Services\Booking\BookingService;
use App\Actions\API\StoreOrderDetailAction;

class StoreOrderApiAction
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

            $order->save();

            
            //order detail
            foreach($this->attributes['details'] as $detail){

                $product = Product::find($detail['product_id']);

                $product->stock = $product->stock - $detail['qty'];
                $product->save();


                dispatch_sync(new StoreOrderDetailAction($detail, $order));

                // delete cart
                if(!$this->attributes['is_direct']){
                    $cart = Cart::where('product_id', $detail['product_id'])
                    ->where('user_id', auth()->user()->id)->first();
                    
                    if($cart) $cart->delete();
                }
            }

            $paramPayment = [];

            if(!$this->attributes['is_offline']){
                $external_id = (string) Str::uuid();
                //create invoice
                $params = [
                    'external_id'           => $external_id,
                    'description'           => 'Product Payment',
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
