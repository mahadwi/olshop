<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'name',
        'email',
        'handphone',
    ];

    public function promoSubscribes() {
        return $this->hasMany(PromoSubscribe::class);
    }
}
