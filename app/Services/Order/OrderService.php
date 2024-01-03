<?php

namespace App\Services\Order;

use Carbon\Carbon;
use App\Models\Order;
use App\Helpers\AppHelper;
use Illuminate\Support\Facades\DB;
use App\Actions\UpdateAllotmentAction;
use App\Repositories\AllotmentRepository;

class OrderService
{
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
    
}
