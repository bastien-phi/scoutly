<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class LinkController
{
    public function index(): Response
    {
        return Inertia::render('links/index');
    }
}
