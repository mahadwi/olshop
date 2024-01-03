<?php

use App\Constants\OrderState;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();            
            $table->foreignId('user_id')->constrained();
            $table->foreignId('address_id')->constrained();
            $table->string('courier');
            $table->string('voucher')->nullable();
            $table->double('ongkir')->default(0);
            $table->double('discount')->default(0);
            $table->double('total');
            $table->string('note')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
