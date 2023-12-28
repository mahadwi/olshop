<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkWithUsCard extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'work_with_us_detail_id',
        'title',
        'title_en',
        'description',
        'description_en',
        'icon',
    ];

    public function workWithUsDetail() {
        return $this->belongsTo(WorkWithUsDetail::class);
    }
}
