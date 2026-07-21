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
        // Перевіряємо, чи таблиця вже існує в базі даних
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                // Зв'язок з категорією (id категорії) та автоматичне видалення товарів, якщо категорію видалено
                $table->foreignId('category_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->text('description');
                $table->decimal('price', 8, 2); // 8 знаків загалом, 2 знаки після коми
                $table->string('image')->nullable(); // nullable дозволить створити товар без картинки
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};