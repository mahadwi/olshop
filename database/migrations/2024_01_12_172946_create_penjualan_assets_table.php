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
        Schema::create('penjualan_assets', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nomor');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');   
            $table->foreignId('pendaftaran_asset_id')->constrained()->onDelete('cascade');
            $table->double('nilai_jual');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_assets');
    }
};
