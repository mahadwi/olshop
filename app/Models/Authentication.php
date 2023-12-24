<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authentication extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'title',
        'title_en',
        'description',
        'description_en',
        'no_hp',
        'link',
    ];

    public function authenticationDetail() {
        return $this->hasMany(AuthenticationDetail::class);
    }
    
}
