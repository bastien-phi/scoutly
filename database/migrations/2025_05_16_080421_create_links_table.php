<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->index()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('author_id')
                ->index()
                ->nullable()
                ->constrained('authors')
                ->nullOnDelete();

            $table->string('title')->nullable();
            $table->string('url');
            $table->text('description')->nullable();

            $table->dateTime('published_at')->nullable();

            $table->timestamps();

            $table->fullText('title');
            $table->fullText('url');
            $table->fullText('description');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
