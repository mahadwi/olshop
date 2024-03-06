<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Order;
use App\Constants\OrderState;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CekOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cek:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek Order Expired and Update to Cancel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::transaction(function () {

            $orders = Order::with(['paymentable', 'orderDetail.product'])->where('status', OrderState::UNPAID)
            ->where('is_offline', false)
            ->whereHas('paymentable', function ($query){
                $query->where('expired', '<', Carbon::now());
            });

            $dataOrder = [];

            if($orders->get()->isNotEmpty()){

                $dataOrder = $orders->pluck('code')->toArray();       

                foreach($orders->get() as $order){
                    foreach($order->orderDetail as $detail){
                        $detail->product->stock += $detail->qty;
                        $detail->product->save();
                    }
                }

                $orders->update(['status' => OrderState::CANCEL]);

            }       

            $stringOrder = implode(', ', $dataOrder);

            \Log::info("Cek Order Berhasil di jalankan, set cancel order " . $stringOrder . ' ' . date('Y-m-d H:i:s'));
        
        });
    }
}
