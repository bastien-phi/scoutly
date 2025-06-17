<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('label')->index();

            $table->timestamps();

            $table->uniqueIndex(['user_id', 'label']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
