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
        Schema::create('penyusutan_assets', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('pendaftaran_asset_id')->constrained()->onDelete('cascade');   
            $table->double('penyusutan');
            $table->double('nilai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyusutan_assets');
    }
};
