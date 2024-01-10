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
        Schema::create('pendaftaran_assets', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nomor');
            $table->foreignId('pembelian_asset_id')->constrained()->onDelete('cascade');   
            $table->foreignId('group_asset_id')->constrained()->onDelete('cascade');
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->string('metode_penyusutan');
            $table->double('tarif_penyusutan');
            $table->unsignedInteger('umur');
            $table->double('nilai_perolehan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_assets');
    }
};
