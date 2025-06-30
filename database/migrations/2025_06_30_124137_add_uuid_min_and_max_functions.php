<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::createFunctionOrReplace(
            name: 'uuid_min',
            parameters: ['first' => 'uuid', 'second' => 'uuid'],
            return: 'uuid',
            language: 'plpgsql',
            body: <<<'PLPGSQL'
            BEGIN
                RETURN LEAST(first, second);
            END;
            PLPGSQL,
            options: [
                'parallel' => 'safe',
                'volatility' => 'immutable',
            ]
        );
        Schema::createFunctionOrReplace(
            name: 'uuid_max',
            parameters: ['first' => 'uuid', 'second' => 'uuid'],
            return: 'uuid',
            language: 'plpgsql',
            body: <<<'PLPGSQL'
            BEGIN
                RETURN GREATEST(first, second);
            END;
            PLPGSQL,
            options: [
                'parallel' => 'safe',
                'volatility' => 'immutable',
            ]
        );

        Schema::getConnection()->statement(
            <<<'SQL'
            CREATE OR REPLACE AGGREGATE min(uuid) (
                SFUNC = uuid_min,
                STYPE = uuid,
                COMBINEFUNC = uuid_min,
                PARALLEL = SAFE,
                SORTOP = operator (>)
            );
            SQL
        );

        Schema::getConnection()->statement(
            <<<'SQL'
            CREATE OR REPLACE AGGREGATE max(uuid) (
                SFUNC = uuid_max,
                STYPE = uuid,
                COMBINEFUNC = uuid_max,
                PARALLEL = SAFE,
                SORTOP = operator (>)
            );
            SQL
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
