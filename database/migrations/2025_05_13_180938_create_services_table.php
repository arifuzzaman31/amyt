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
        Schema::create('services', function (Blueprint $table) {  // Renamed from Sales [cite: 4]
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->date('service_date');
            $table->string('invoice_no');
            $table->decimal('discount')->nullable();
            $table->timestamps();
        });

        Schema::create('service_items', function (Blueprint $table) { //renamed from sales_items
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('yarn_count_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity');
            $table->decimal('unit_price');
            $table->decimal('extra_quantity')->nullable(); // [cite: 22]
            $table->decimal('extra_quantity_price')->nullable(); // [cite: 23]
            $table->string('color')->nullable(); // [cite: 19]
            $table->string('bag_poly')->nullable(); // Assuming this is how it's stored
            $table->decimal('gross_weight')->nullable();  // [cite: 19]
            $table->decimal('net_weight')->nullable();    // [cite: 19]
            $table->integer('bobin')->nullable();         // [cite: 19]
            $table->string('remark')->nullable();         // [cite: 19, 21]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_items');
        Schema::dropIfExists('services');
    }
};
