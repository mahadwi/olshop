<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoSubscribe extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'promo_id',
        'email_id',
        'status',
    ];

    public function email() {
        return $this->belongsTo(Email::class);
    }

    public function promo() {
        return $this->belongsTo(Promo::class);
    }
}
