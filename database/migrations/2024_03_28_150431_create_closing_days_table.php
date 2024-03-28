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
        Schema::create('closing_days', function (Blueprint $table) {
            $table->id();
            $table->datetime('open');
            $table->datetime('close');
            $table->double('starting_cash');
            $table->double('cash_in')->default(0);
            $table->double('cash_out')->default(0);
            $table->double('total_cash')->default(0);
            $table->double('total_return')->default(0);
            $table->foreignIdFor(User::class, 'kasir_open')->nullable();
            $table->foreignIdFor(User::class, 'kasir_close')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('closing_days');
    }
};
