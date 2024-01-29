<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operational extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'open',
        'close',
        'is_open'
    ];

    protected $appends = ['time'];

    protected $hidden = ['created_at', 'updated_at'];

    function getTimeAttribute(){
        return [$this->open, $this->close];
    }

}
