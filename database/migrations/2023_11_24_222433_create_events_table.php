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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cover');
            $table->string('banner');
            $table->text('description');
            $table->string('place');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('time_start');
            $table->string('time_end');
            $table->string('maps');
            $table->string('maps_address');
            $table->string('detail_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
