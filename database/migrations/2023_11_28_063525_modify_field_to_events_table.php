<?php

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
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['maps_address', 'detail_address']);
            $table->text('maps')->change();
            $table->text('detail_maps');
            $table->foreignIdFor(User::class, 'created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('maps_address');
            $table->string('detail_address');
            $table->dropColumn(['detail_maps', 'created_by']);
        });
    }
};
