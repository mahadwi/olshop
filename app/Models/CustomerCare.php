<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class CustomerCare extends Model
{
    use HasFactory;

    protected $table = 'customer_care';

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'title',
        'description',
    ];

}
