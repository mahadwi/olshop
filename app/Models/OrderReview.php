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

    protected $appends = ['date_review'];

    function getDateReviewAttribute() {
        return $this->created_at->format(config('app.default.datetime_human'));
    }

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

   
}
