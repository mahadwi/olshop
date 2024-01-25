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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('external_id')->nullable()->change();
            $table->string('invoice_url')->nullable()->change();
            $table->datetime('expired')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('external_id')->nullable(false)->change();
            $table->string('invoice_url')->nullable(false)->change();
            $table->string('expired')->nullable(false)->change();
            
        });
    }
};
