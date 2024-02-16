<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'commission_id',
        'min',
        'max',
        'percent',
    ];

    public function setMinAttribute($value)
    {
        $this->attributes['min'] = str_replace('.', '', $value);
    }

    public function setMaxAttribute($value)
    {
        $this->attributes['max'] = str_replace('.', '', $value);
    }

    // public function getMinAttribute($value)
    // {
    //     return number_format($value, 0, ',', '.');
    // }

    // public function getMaxAttribute($value)
    // {
    //     return number_format($value, 0, ',', '.');
    // }

    protected $hidden = ['created_at', 'updated_at'];
}
