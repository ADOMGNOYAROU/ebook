<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('author', 150);
            $table->string('file_path');
            $table->string('cover_path');
            $table->integer('file_size'); // Taille en Ko
            $table->integer('pages')->nullable();
            $table->string('language', 10)->default('fr');
            $table->boolean('is_free')->default(true);
            $table->unsignedInteger('downloads_count')->default(0);
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Index pour optimiser les recherches
            $table->index(['slug', 'author', 'category_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ebooks');
    }
};