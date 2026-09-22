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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('material_id')->nullable()->constrained('materials');
            $table->foreignId('finishing_id')->nullable()->constrained('finishings');
            $table->integer('quantity');
            $table->decimal('custom_width', 8, 2)->nullable();
            $table->decimal('custom_height', 8, 2)->nullable();
            $table->string('raw_design_file')->nullable();
            $table->longText('preview_mockup_file')->nullable();
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
