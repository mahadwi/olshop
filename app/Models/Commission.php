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
        dd($this->category_id);
        $categories = ProductCategory::whereIn($this->category_id)->get();

        dd($categories);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    
}
