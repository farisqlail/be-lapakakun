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
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->string('duration');
            $table->integer('max_user');
            $table->decimal('price', 12, 2);
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->longText('scheme')->nullable();
            $table->longText('information')->nullable();
            $table->json('benefit')->nullable(); // disimpan sebagai array
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
