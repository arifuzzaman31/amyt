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
            $table->string('document_link')->nullable();
            $table->longText('description')->nullable();
            $table->longText('addition_info')->nullable();
            $table->decimal('discount')->nullable();
            $table->timestamps();
        });

        Schema::create('service_items', function (Blueprint $table) { //renamed from sales_items
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('yarn_count_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity');
            $table->foreignId('unit_attr_id')->constrained('attributes')->onDelete('cascade');
            $table->decimal('unit_price');
            $table->decimal('extra_quantity')->nullable(); // [cite: 22]
            $table->decimal('extra_quantity_price')->nullable(); // [cite: 23]
            $table->foreignId('color_id')->constrained('attributes')->onDelete('cascade');
            //$table->string('bag_poly')->nullable(); // Assuming this is how it's stored
            $table->decimal('gross_weight')->nullable();  // [cite: 19]
            $table->decimal('net_weight')->nullable();
            $table->foreignId('weight_attr_id')->constrained('attributes')->onDelete('cascade');    // [cite: 19]
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
