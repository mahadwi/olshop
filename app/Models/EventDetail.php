<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'time_start',
        'time_end',
        'contact',
        'price',
        'is_refundable',
    ];

    protected $casts = [
        'date' => 'date:d-m-Y',
    ];

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::parse($value)->format('Y-m-d');
    }
 
}
