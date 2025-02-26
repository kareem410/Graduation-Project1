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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->decimal('price', 10, 2);
            $table->string('unit')->nullable();
            $table->boolean('stock_availability')->default(true);
            $table->integer('counts')->default(0);
            $table->text('description')->nullable();
            $table->decimal('rating', 3, 2)->default(0.0);
            $table->boolean('offer')->default(false);
            $table->boolean('is_best_deal')->default(false);
            $table->boolean('top_selling')->default(false);
            $table->boolean('everyday_needs')->default(false);
            $table->string('image')->nullable();
            $table->boolean('new_arrival')->default(false);
            $table->string('barcode')->unique();
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
