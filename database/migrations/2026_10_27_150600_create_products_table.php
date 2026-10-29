<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->string('num')->nullable();
            $table->string('name');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->text('description_long')->nullable();
            $table->jsonb('features')->nullable();
            $table->integer('price')->default(0);
            $table->string('accent')->nullable();
            $table->string('accent_strong')->nullable();
            $table->boolean('use_img')->default(false);
            $table->string('image')->nullable();
            $table->jsonb('details')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
