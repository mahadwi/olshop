<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'history_en',
        'status',
        'confirm_date',
        'product_deadline',
        'note',
    ];

    protected $appends = ['entry_date'];

    protected $casts = [
        'confirm_date' => 'date:d-m-Y',
    ];

    public function getEntryDateAttribute()
    {
        return Carbon::parse($this->created_at)->format(config('app.default.datetime_human'));
    }

    public function setConfirmDateAttribute($value)
    {
        $this->attributes['confirm_date'] = $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function setProductDeadlineAttribute($value)
    {
        $this->attributes['product_deadline'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function imageable()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function imageName()
    {
        return $this->imageable()->pluck('name');
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

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function agreements()
    {
        return $this->hasMany(VendorAgreement::class)->orderBy('id');
    }
}
