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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('type')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->index(['name', 'company_name','phone']);
        });

        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->uuid('auto_generated_id')->nullable();
            $table->date('purchase_date');
            $table->string('challan_no')->nullable();
            $table->string('document_link')->nullable();
            $table->double('total_amount')->default(0);
            $table->tinyInteger('payment_status')->default(0)->comment('0=due,1=>paid,2=>refunded');
            $table->decimal('discount')->default(0);
            $table->tinyInteger('discount_type')->nullable()->comment('1=fixed,0=>percentage');
            $table->tinyInteger('status')->default(0)->comment('0=pending,1=>approved,2=>rejected,3=>draft,4=>close'); // Paid or Due [cite: 2, 3]
            $table->longText('additional_info')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->index(['challan_no', 'purchase_date']);
        });

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
            $table->foreignId('yarn_count_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity');
            $table->decimal('unit_price');
            $table->timestamps();
            $table->index(['purchase_id', 'yarn_count_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('suppliers');
    }
};
