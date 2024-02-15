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
        Schema::create('commission_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commission_id')->constrained(); 
            $table->double('min')->default(0);
            $table->double('max')->default(0);
            $table->unsignedInteger('percent')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_details');
    }
};
