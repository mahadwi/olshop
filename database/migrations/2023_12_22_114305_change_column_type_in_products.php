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
        Schema::table('products', function (Blueprint $table) {
            $table->double('price', 8, 2)->nullable()->change();
            $table->double('sale_price', 8, 2)->nullable()->change();
            $table->double('price_usd', 8, 2)->nullable()->change();
            $table->double('sale_usd', 8, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('price')->nullable()->change();
            $table->integer('sale_price')->nullable()->change();
            $table->integer('price_usd')->nullable()->change();
            $table->integer('sale_usd')->nullable()->change();
        });
    }
};
