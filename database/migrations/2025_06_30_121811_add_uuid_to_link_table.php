<?php

declare(strict_types=1);

use App\Models\Link;
use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('links', function (Blueprint $table): void {
            $table->uuid()->nullable();
        });

        Link::query()->each(function (Link $link): void {
            $link->setUniqueIds();
            $link->save();
        });

        Schema::table('links', function (Blueprint $table): void {
            $table->uuid()->nullable(false)->change();

            $table->uniqueIndex('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('links', function (Blueprint $table): void {
            $table->dropColumn('uuid');
        });
    }
};
