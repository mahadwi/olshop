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
        Schema::create('group_assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('coa_id');
            $table->unsignedBigInteger('coa_akumulasi_id');
            $table->unsignedBigInteger('coa_depresiasi_id');
            $table->unsignedInteger('umur');
            $table->string('metode_penyusutan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_assets');
    }
};
