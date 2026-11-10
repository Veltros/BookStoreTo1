<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->string('title', 255);
            $table->string('author', 150);
            $table->string('publisher', 150)->nullable();
            $table->string('isbn', 30)->nullable();
            $table->integer('year')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(0);
            $table->text('description')->nullable();
            $table->string('cover_image', 255)->default('default_book.png');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
