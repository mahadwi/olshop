<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReview extends Model
{
    use HasFactory;


    protected $fillable = [
        'order_detail_id',        
        'review',
        'rating',        
        'user_id'
    ];

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function imageable()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function imageName()
    {
        return $this->imageable()->pluck('name');
    }
}
