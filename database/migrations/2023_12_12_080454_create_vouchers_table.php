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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('capacity');
            $table->unsignedBigInteger('quota')->default(0);
            $table->string('use_for');
            $table->string('type');
            $table->unsignedInteger('disc_percent');
            $table->double('disc_price');
            $table->double('min_price');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('time_start');
            $table->string('time_end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
