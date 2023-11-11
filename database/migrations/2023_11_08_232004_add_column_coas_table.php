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
        Schema::table('coas', function (Blueprint $table) {
            $table->dropColumn('saldo_awal');
            $table->boolean('is_saldo_awal')->default(true);
            $table->string('normal_balance')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coas', function (Blueprint $table) {
            $table->integer('saldo_awal');        
            $table->dropColumn('is_saldo_awal');
            $table->string('normal_balance')->nullable(false)->change();
        });
    }
};
