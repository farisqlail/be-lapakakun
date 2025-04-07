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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('transaction_code', 5)->unique();
            $table->string('customer_name');
            $table->string('customer_number');
            $table->foreignId('id_product')->constrained('products');
            $table->foreignId('id_category')->constrained('categories');
            $table->enum('status_payment', ['pending', 'paid', 'cancel'])->default('pending');
            $table->decimal('discount', 8, 2)->default(0);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
