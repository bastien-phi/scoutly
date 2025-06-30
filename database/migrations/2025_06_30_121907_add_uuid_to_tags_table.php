<?php

declare(strict_types=1);

use App\Models\Tag;
use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tags', function (Blueprint $table): void {
            $table->uuid()->nullable();
        });

        Tag::query()->each(function (Tag $tag): void {
            $tag->setUniqueIds();
            $tag->save();
        });

        Schema::table('tags', function (Blueprint $table): void {
            $table->uuid()->nullable(false)->change();

            $table->uniqueIndex('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table): void {
            $table->dropColumn('uuid');
        });
    }
};
