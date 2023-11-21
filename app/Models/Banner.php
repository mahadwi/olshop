<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'title',
        'description',
    ];

    public function imageable()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function imageName()
    {
        return $this->imageable()->pluck('name');
    }
}
