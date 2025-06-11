<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Services\Database\PostgresGrammar;
use Illuminate\Database\Events\ConnectionEstablished;

class BindPostgresGrammar
{
    public function handle(ConnectionEstablished $event): void
    {
        $connection = $event->connection;

        if ($connection->getDriverName() !== 'pgsql') {
            return;
        }

        $connection->setQueryGrammar(new PostgresGrammar($connection));
    }
}
