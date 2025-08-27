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
        Schema::create('dashboard_daily_customers', function (Blueprint $table) {
            $table->date('date')->primary();
            $table->unsignedInteger('new_customers')->default(0);
            $table->unsignedInteger('active_customers')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_daily_customers');
    }
};
