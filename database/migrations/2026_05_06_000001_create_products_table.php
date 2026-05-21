<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('litre')->nullable();
            $table->decimal('price_unit', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->date('expiration_date')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamp('archive')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};