<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('link_tag', function (Blueprint $table): void {
            $table->foreignId('link_id')
                ->constrained('links')
                ->cascadeOnDelete();

            $table->foreignId('tag_id')
                ->constrained('tags')
                ->cascadeOnDelete();

            $table->primary(['link_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('link_tag');
    }
};
