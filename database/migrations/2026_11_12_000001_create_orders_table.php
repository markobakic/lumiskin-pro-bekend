<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable()->unique(); // npr. LSP-000042, popunjava se posle insert-a
            $table->string('status')->default('new')->index();

            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('postal_code', 20);
            $table->string('city');
            $table->text('note')->nullable();          // interna napomena admina

            // Iznosi u RSD (celi brojevi), izračunati na bekendu iz cena u bazi
            $table->integer('subtotal');
            $table->integer('discount')->default(0);
            $table->integer('total');
            $table->string('discount_code', 20)->nullable();
            $table->unsignedTinyInteger('discount_percent')->default(0);

            $table->string('payment_method')->default('cod'); // pouzećem
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            // proizvod može kasnije da se obriše — zato i snapshot naziva/cene
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_slug');
            $table->string('product_name');
            $table->integer('unit_price');
            $table->unsignedInteger('quantity');
            $table->integer('line_total');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
