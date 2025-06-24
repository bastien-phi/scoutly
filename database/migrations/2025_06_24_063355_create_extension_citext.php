<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::createExtensionIfNotExists('citext');
    }

    public function down(): void
    {
        Schema::dropExtensionIfExists('citext');
    }
};
