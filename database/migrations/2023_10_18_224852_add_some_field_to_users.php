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
        Schema::table('users', function (Blueprint $table) {
            $table->string('bank')->nullable();
            $table->string('bank_account_holder')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('ktp', 16)->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bank', 'bank_account_holder', 'bank_account_number', 'nik', 'address', 'is_active']);
        });
    }
};
