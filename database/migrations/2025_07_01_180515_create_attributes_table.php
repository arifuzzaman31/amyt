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
            $table->string('name')->unique()->comment('Name of the attribute');
            $table->string('slug')->unique()->comment('Slug for the attribute');
            $table->string('type')->comment('Type of the attribute, e.g., weight, color, size');
            $table->string('code')->nullable()->comment('Default value for the attribute');
            $table->text('description')->nullable()->comment('Description of the attribute');
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
