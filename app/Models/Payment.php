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
        'payment_method',
        'payment_channel',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'date:d-m-Y H:i:s',
    ];

    public function paymentable()
    {
        return $this->morphTo();
    }
}
