<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand_id',
        'description',
        'product_category_id',
        'vendor_id',
        'price',
        'price_usd',
        'history',
        'condition',
        'commission_type',
        'commission',
        'display_on_homepage',
        'sale_price',
        'sale_usd',
        'color_id',
        'weight',
        'length',
        'width',
        'height',
        'description_en',
        'history_en'
    ];
}
