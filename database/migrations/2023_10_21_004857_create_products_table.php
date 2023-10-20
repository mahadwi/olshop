<?php

use App\Models\Brand;
use App\Models\User;
use App\Models\ProductCategory;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(ProductCategory::class);
            $table->string('name');
            $table->text('description');
            $table->string('history');
            $table->foreignIdFor(Brand::class);
            $table->integer('stock');
            $table->string('image');
            $table->integer('price');
            $table->date('entry_date');
            $table->date('expired_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
