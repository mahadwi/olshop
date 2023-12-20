<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'paymentable_id',
        'paymentable_type',
        'status',
        'external_id',
        'invoice_url',
        'expired',
    ];

    // protected $casts = [
    //     'expired' => 'date:d-m-Y',
    // ];

    public function paymentable()
    {
        return $this->morphTo();
    }
}
