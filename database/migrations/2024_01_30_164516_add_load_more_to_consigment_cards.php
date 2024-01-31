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
        Schema::table('consignment_cards', function (Blueprint $table) {
            $table->string('loadmore_type')->nullable();
            $table->string('loadmore_link')->nullable();
            $table->string('loadmore_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consignment_cards', function (Blueprint $table) {
            $table->dropColumn(['loadmore_type', 'loadmore_link', 'loadmore_text']);
        });
    }
};
