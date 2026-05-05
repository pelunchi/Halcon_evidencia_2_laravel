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
            $table->string('invoice_number')->unique();
            $table->string('customer_number');
            $table->string('customer_name');
            $table->text('fiscal_data')->nullable();
            $table->dateTime('order_date');
            $table->text('delivery_address');
            $table->text('notes')->nullable();
            $table->enum('status', ['ordered', 'in_process', 'in_route', 'delivered'])->default('ordered');
            $table->string('load_photo')->nullable();
            $table->string('delivery_photo')->nullable();
            $table->boolean('deleted')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            // Indexes for frequent queries
            $table->index('invoice_number');
            $table->index('customer_number');
            $table->index('status');
            $table->index('deleted');
            $table->index('order_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
