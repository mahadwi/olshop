<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand_id',
        'description',
        'product_category_id',
        'user_id',
        'stock',
        'price',
        'history',
        'entry_date',
        'expired_date',
        'condition',
        'is_active',
        'commission_type',
        'commission',
        'display_on_homepage',
        'sale_price',
        'color_id',
        'weight',
        'length',
        'width',
        'height'
    ];

    protected $appends = ['status'];

    protected $casts = [
        'entry_date' => 'date:d-m-Y',
        'expired_date' => 'date:d-m-Y',
    ];

    function getStatusAttribute() {
        return $this->is_active ? __('app.label.active') : __('app.label.not_active');
    }

    protected static function booted(): void
    {
        // static::deleted(function (Product $product) {
        //     //also delete file if exist
        //     $imageFile = public_path('image/product/'.$product->image);
        //     if(File::exists($imageFile)){                
        //         //delete file
        //         File::delete($imageFile);
        //     }                
        // });
    }

    public function setEntryDateAttribute($value)
    {
        $this->attributes['entry_date'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function setExpiredDateAttribute($value)
    {
        $this->attributes['expired_date'] = Carbon::parse($value)->format('Y-m-d');
    }    

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
