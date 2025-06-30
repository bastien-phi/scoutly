<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->uuid()->nullable();
        });

        User::query()->each(function (User $user): void {
            $user->setUniqueIds();
            $user->save();
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->uuid()->nullable(false)->change();

            $table->uniqueIndex('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('uuid');
        });
    }
};
