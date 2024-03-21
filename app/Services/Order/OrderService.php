<?php

namespace App\Services\Order;

use Carbon\Carbon;
use App\Models\Order;
use App\Helpers\AppHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDF;


class OrderService
{

    protected $rootPath;

    public function __construct()
    {
        $this->rootPath = public_path('file/invoice');
    }

    public function generateCode()
    {
        
        $month = AppHelper::BulanRomawi(Carbon::now()->format('m'));
        
        $year = Carbon::now()->format('y');
        
        $period = 'POS/'.$month.'/'.$year;   
        
        $length = strlen($period);

        $queryBuilder = Order::selectRaw('max(substring("code", 1, 4)) as kode');
        $queryBuilder->where(DB::raw('substring("code", 6, '.$length.')'), '=', $period);

        $exists = $queryBuilder->first();

        if (!$exists->kode) {
            $sequence = '0001';
        } else {
            $sequence = (int) $exists->kode + 1;
            $sequence = str_pad($sequence, 4, "0", STR_PAD_LEFT);
        }

        $code = $sequence .'/' . $period;

        return $code;
    }

    public function getInvoice($order)
    {
        $newCode = 'INV-'.preg_replace('/\//', '-', $order->code).'.pdf';

        $invoicePath = "{$this->rootPath}/$newCode";

        if(!File::exists($invoicePath)){
            
           $this->generateInvoice($order);

        }

        return asset("file/invoice/{$newCode}");
    }

    public function generateInvoice($order)
    {

        $newCode = 'INV-'.preg_replace('/\//', '-', $order->code).'.pdf';

        $invoicePath = "{$this->rootPath}/$newCode";

        $profile = AppHelper::profile();

        $pdf = PDF::loadview('mail.invoice', compact('order', 'profile'));    

        $pdf->save($invoicePath);
    }

    
}
