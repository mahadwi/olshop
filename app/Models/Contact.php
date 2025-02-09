<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'telp',
        'maps',
        'link',
        'email',
        'facebook',
        'instagram',
        'tiktok',
        'address',
        'address_en',
    ];
}
