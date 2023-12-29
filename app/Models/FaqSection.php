<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class FaqSection extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    public function faqQuestionAnswer() {
        return $this->hasMany(FaqQuestionAnswer::class);
    }
}
