<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'category_id',
    ];
    
    protected $casts = [
        'category_id' => 'array'
    ];

    protected $appends = ['category'];


    function getCategoryAttribute() {
        $categories = ProductCategory::whereIn('id', $this->category_id)
        ->pluck('name')->implode(', ');

        return $categories;
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function details()
    {
        return $this->hasMany(CommissionDetail::class);
    }

    
}
