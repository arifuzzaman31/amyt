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
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('type')->comment('Type of the attribute, e.g., weight, color, size');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable()->comment('Icon associated with the attribute');
            $table->string('group')->nullable()->comment('Group to which the attribute belongs, e.g., product, user');
            $table->boolean('is_active')->default(true)->comment('Whether the attribute is active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
