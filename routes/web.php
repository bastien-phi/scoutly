<?php

declare(strict_types=1);

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::controller(LinkController::class)
        ->prefix('links')
        ->name('links.')
        ->group(function (): void {
            Route::get('/', 'index')->name('index');
            Route::get('{link}', 'show')
                ->name('show')
                ->can('view', 'link');
        });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
