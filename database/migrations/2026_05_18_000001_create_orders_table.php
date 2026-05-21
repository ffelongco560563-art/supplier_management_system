<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->text('message_instructions')->nullable();
            $table->decimal('total_price', 10, 2)->default(0);
            $table->longText('product_details')->nullable();
            $table->string('status')->default('Pending');
            $table->text('decline_reason')->nullable();
            $table->string('truck_id')->nullable();
            $table->decimal('payment_amount', 10, 2)->nullable();
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->string('payment_method')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};