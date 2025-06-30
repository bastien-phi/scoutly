<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authors', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name')->index();

            $table->timestamps();

            $table->uniqueIndex(['user_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};
