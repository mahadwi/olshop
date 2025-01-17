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
        'resi',
        'resi_date',
        'courier_service',
        'is_taken',
        'jumlah',
        'pembulatan',
        'pay',
        'return',
        'is_pos',
        'kasir_id',
    ];

    protected $casts = [
        'created_at'      => 'date:d-m-Y',
        'pickup_deadline' => 'date:d-m-Y',
    ];

    protected $appends = ['orderDate', 'resiDateFix', 'newCode'];


    protected static function boot()
    {
        parent::boot();
        static::creating(function (Order $order) {
            if ($order->code == null) {
                $order->code = (new OrderService)->generateCode();
            }
        });
    }

    function getOrderDateAttribute() {
        return $this->created_at->format(config('app.default.datetime_human')); 
    }

    function getResiDateFixAttribute() {
        $resiDate = $this->resi_date ? Carbon::parse($this->resi_date) : Carbon::now();
        return $resiDate->format(config('app.default.datetime_human')); 
    }

    function getNewCodeAttribute() {
        return 'INV-'.preg_replace('/\//', '-', $this->code).'.pdf';
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

    public function reviews()
    {
        return $this->hasManyThrough(OrderReview::class, OrderDetail::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }
}
