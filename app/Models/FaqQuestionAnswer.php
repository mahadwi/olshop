<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqQuestionAnswer extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'faq_section_id',
        'title',
        'title_en',
        'description',
        'description_en',
    ];

    public function faqsection() {
        return $this->belongsTo(FaqSection::class);
    }
}
