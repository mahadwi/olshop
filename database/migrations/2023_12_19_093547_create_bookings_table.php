<?php

use App\Models\EventDetail;
use App\Models\User;
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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignIdFor(EventDetail::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->double('price');
            $table->unsignedInteger('qty');        
            $table->string('voucher')->nullable();
            $table->double('total');            
            $table->text('message')->nullable();            
            $table->text('note')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
