<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_cash')->default(false);
            $table->double('pay')->default(0);
            $table->double('return')->default(0);
            $table->double('pembulatan')->default(0);
            $table->double('jumlah')->default(0);
            $table->foreignIdFor(User::class, 'kasir_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['is_cash', 'pay', 'return', 'pembulatan', 'jumlah']);
        });
    }
};
