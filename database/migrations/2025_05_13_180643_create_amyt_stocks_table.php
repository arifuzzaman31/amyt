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
        Schema::create('amyt_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('yarn_count_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity')->default(0); // Current stock quantity
            $table->timestamps();
        });

        Schema::create('customer_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade')->index();
            $table->foreignId('yarn_count_id')->constrained()->onDelete('cascade');
            $table->string('date')->nullable();
            $table->decimal('quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amyt_stocks');
        Schema::dropIfExists('customer_stocks');
    }
};
