<?php

declare(strict_types=1);

use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

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
