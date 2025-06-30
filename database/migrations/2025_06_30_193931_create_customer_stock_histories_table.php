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
        Schema::create('customer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->uuid('auto_generated_id')->nullable();
            $table->date('in_date');
            $table->string('challan_no')->nullable();
            $table->string('document_link')->nullable();
            $table->double('total_amount')->default(0);
            $table->tinyInteger('payment_status')->default(0)->comment('0=due,1=>paid,2=>refunded');
            $table->decimal('discount')->default(0);
            $table->tinyInteger('discount_type')->nullable()->comment('1=fixed,0=>percentage');
            $table->tinyInteger('status')->default(0)->comment('0=pending,1=>approved,2=>rejected,3=>draft,4=>close'); // Paid or Due [cite: 2, 3]
            $table->longText('additional_info')->nullable();
            $table->longText('description')->nullable();
            $table->tinyInteger('is_stocked')->default(0)->comment('0=not stock,1=>stock'); // 0=not stock, 1=stock
            $table->timestamps();
            $table->index(['challan_no', 'in_date']);
        });

        Schema::create('customer_stock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('yarn_count_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity')->default(0);
            $table->decimal('unit_price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_stock_histories');
    }
};
