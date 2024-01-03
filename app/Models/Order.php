<?php

namespace App\Models;

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
    ];

    protected $casts = [
        'created_at' => 'date:d-m-Y',
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
