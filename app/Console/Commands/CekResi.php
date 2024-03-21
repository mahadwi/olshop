<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Order;
use App\Constants\OrderState;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\Ongkir\OngkirService;
use App\Actions\SetCompletedOrderAction;

class CekResi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cek:resi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek Resi And Auto Update Order Status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::transaction(function () {
            $orders = Order::where('status', OrderState::ONGOING)->get();
            $dataOrder = [];
            
            foreach($orders as $order){

                $now         = Carbon::now();
                $cekResi = (new OngkirService)->cekResi($order->courier, $order->resi);

                if($cekResi['status'] == 200){
                    $statusOngkir = $cekResi['data']['summary']['status'];

                    if($statusOngkir == 'DELIVERED'){            
                        $dateDeliver = Carbon::parse($cekResi['data']['summary']['date']);        
                        $diffDay = $dateDeliver->diffInDays($now);

                        if($diffDay > 3){
                            // $order->status = OrderState::COMPLETED;
                            // $order->save();

                            (new SetCompletedOrderAction($order))->handle();

                            array_push($dataOrder, $order->code);

                        }


                    }
                }
            };

            $stringOrder = implode(', ', $dataOrder);

            \Log::info("Cek Resi Berhasil di jalankan " . $stringOrder . ' ' . date('Y-m-d H:i:s'));
        });        
    }
}
