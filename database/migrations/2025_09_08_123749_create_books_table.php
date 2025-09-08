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
        Schema::create('books', function (Blueprint $table) {

            $table->id();

            $table->string('title');
            $table->string('isbn')->unique();
            $table->string('genre')->nullable();
            $table->string('cover_image')->nullable();

            $table->text('description')->nullable();

            $table->integer("total_copies")->nullable();
            $table->integer("available_copies")->nullable();

            $table->decimal('price', 8, 2)->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->foreignId('author_id')->constrained('authors')->onDelete('cascade');

            $table->date('published_at')->nullable();
            $table->timestamps();

            $table->index(['title', 'author_id']);
            $table->index('isbn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
