<?php

namespace App\Actions;

use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\File\File;

class StoreVoucherAction
{
    private $attributes;
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        return DB::transaction(function () {

            $voucher = new Voucher($this->attributes);
        
            $voucher->save();
            
            return $voucher;
        });
       
    }
}
