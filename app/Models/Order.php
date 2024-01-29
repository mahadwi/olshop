<?php

namespace App\Models;

use Carbon\Carbon;
use App\Services\Order\OrderService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'address_id',        
        'courier',
        'ongkir',        
        'voucher',        
        'discount',        
        'total',        
        'status',        
        'note',        
        'is_offline',        
        'pickup_deadline',        
    ];

    protected $casts = [
        'created_at'      => 'date:d-m-Y',
        'pickup_deadline' => 'date:d-m-Y',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (Order $order) {
            if ($order->code == null) {
                $order->code = (new OrderService)->generateCode();
            }
        });
    }

    public function setPickupDeadlineAttribute($value)
    {
        $this->attributes['pickup_deadline'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function paymentable()
    {
        return $this->morphOne(Payment::class, 'paymentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
