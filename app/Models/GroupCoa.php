<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupCoa extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'normal_balance',
        'is_active',
    ];

    protected $appends = ['status'];

    function getStatusAttribute() {
        return $this->is_active ? __('app.label.active') : __('app.label.not_active');
    }
}
