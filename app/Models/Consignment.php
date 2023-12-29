<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consignment extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'title',
        'title_en',
        'description',
        'description_en',
    ];

    public function consignmentDetail() {
        return $this->hasMany(ConsignmentDetail::class)->orderBy('section');
    }
}
