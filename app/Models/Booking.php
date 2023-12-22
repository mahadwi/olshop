<?php

namespace App\Models;

use Carbon\Carbon;
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

    protected $appends = ['booking_time'];


    protected static function boot()
    {
        parent::boot();
        static::creating(function (Booking $booking) {
            if ($booking->code == null) {
                $booking->code = (new BookingService)->generateCode();
            }
        });
    }

    public function getBookingTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format(config('app.default.datetime_human'));
    }

    public function paymentable()
    {
        return $this->morphOne(Payment::class, 'paymentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
