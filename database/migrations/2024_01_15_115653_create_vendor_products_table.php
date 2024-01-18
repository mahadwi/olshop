<?php

use App\Models\Color;
use App\Models\Vendor;
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
        Schema::create('vendor_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->text('description_en');
            $table->foreignId('vendor_id')->constrained();   
            $table->foreignId('product_category_id')->constrained();   
            $table->foreignId('brand_id')->constrained();   
            $table->foreignId('color_id')->constrained();   
            $table->string('condition');
            $table->text('history');
            $table->text('history_en');
            $table->double('price');
            $table->double('sale_price');
            $table->double('price_usd');
            $table->double('sale_usd');
            $table->string('commission_type');
            $table->unsignedInteger('commission');
            $table->double('weight')->default(1);
            $table->double('length')->default(0);
            $table->double('width')->default(0);
            $table->double('height')->default(0);            
            $table->string('status');
            $table->date('confirm_date')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_products');
    }
};
