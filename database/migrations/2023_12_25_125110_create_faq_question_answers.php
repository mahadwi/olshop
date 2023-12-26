<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faq_question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faq_section_id')->constrained();
            $table->string('title');
            $table->string('title_en');
            $table->text('description');
            $table->text('description_en');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_question_answers');
    }
};
