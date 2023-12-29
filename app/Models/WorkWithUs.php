<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkWithUs extends Model
{
    use HasFactory;

    protected $table = 'work_with_uses';

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'title',
        'title_en',
        'description',
        'description_en',
    ];

    public function workWithUsDetail() {
        return $this->hasMany(WorkWithUsDetail::class);
    }
}
