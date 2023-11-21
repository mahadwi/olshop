<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',        
        'user_id',        
        'tag',
        'is_primary',
        'subdistrict_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class);
    }
}
