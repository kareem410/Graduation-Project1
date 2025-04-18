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
            $table->boolean('stockAvailability')->default(true);
            $table->integer('inStock')->default(0);
            $table->text('description')->nullable();
            $table->decimal('rating', 3, 2)->default(0.0);
            $table->boolean('offers')->default(false);
            $table->boolean('bestDeal')->default(false);
            $table->boolean('topSelling')->default(false);
            $table->boolean('everydayNeeds')->default(false);
            $table->string('imageUrl')->nullable();
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
