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
        Schema::create('pembelian_assets', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nomor');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('jatuh_tempo');
            $table->date('tanggal_jatuh_tempo');
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('qty');
            $table->double('price');
            $table->string('jenis_ppn');
            $table->double('total');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_assets');
    }
};
