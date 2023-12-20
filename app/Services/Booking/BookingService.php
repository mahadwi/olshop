<?php

namespace App\Services\Booking;

use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use App\Actions\UpdateAllotmentAction;
use App\Repositories\AllotmentRepository;

class BookingService
{
    public function generateCode()
    {
        $day = Carbon::now()->format('d');
        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('y');
        $prefix = 'BK' . $day . $month . $year;

        $last = Booking::where('code', 'like', "{$prefix}%")
            ->latest('code')
            ->first();
        
        $lastNumber = $last ? intval(substr($last->code, -2)) : 0;

        $nextNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);

        $code = $prefix.$nextNumber;

        return $code;
    }
    
}
