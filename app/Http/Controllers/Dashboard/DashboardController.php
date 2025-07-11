<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use Inertia\Inertia;
use Inertia\Response;

class DashboardController
{
    public function __invoke(): Response
    {
        return Inertia::render('dashboard');
    }
}
