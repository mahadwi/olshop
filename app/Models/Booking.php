<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Booking\BookingService;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'event_detail_id',
        'user_id',
        'price',        
        'qty',        
        'voucher',        
        'total',        
        'message',        
        'note',        
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (Booking $booking) {
            if ($booking->code == null) {
                $booking->code = (new BookingService)->generateCode();
            }
        });
    }

    public function paymentable()
    {
        return $this->morphOne(Payment::class, 'paymentable');
    }
}
