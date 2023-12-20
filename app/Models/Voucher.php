<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'capacity',
        'quota',
        'use_for',
        'type',
        'disc_percent',
        'disc_price',
        'min_price',
        'start_date',
        'end_date',
        'time_start',
        'time_end',
    ];

    protected $casts = [
        'start_date' => 'date:d-m-Y',
        'end_date' => 'date:d-m-Y',
    ];

    protected $appends = ['duration', 'date', 'time'];

    protected $hidden = ['created_at', 'updated_at'];

    function getDurationAttribute(){
        return $this->start_date->format('d-m-Y') . ' - ' . $this->end_date->format('d-m-Y') . ' | ' . $this->time_start . ' - ' . $this->time_end;
    }

    function getDateAttribute(){
        return [$this->start_date->format('d-m-Y'), $this->end_date->format('d-m-Y')];
    }

    function getTimeAttribute(){
        return [$this->time_start, $this->time_end];
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = Carbon::parse($value)->format('Y-m-d');
    }
}
