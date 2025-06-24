<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->caseInsensitiveText('name')->change();
        });
    }

    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->string('name')->change();
        });
    }
};
