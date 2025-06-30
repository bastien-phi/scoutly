<?php

declare(strict_types=1);

use App\Models\Author;
use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table): void {
            $table->uuid()->nullable();
        });

        Author::query()->each(function (Author $author): void {
            $author->setUniqueIds();
            $author->save();
        });

        Schema::table('authors', function (Blueprint $table): void {
            $table->uuid()->nullable(false)->change();

            $table->uniqueIndex('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table): void {
            $table->dropColumn('uuid');
        });
    }
};
