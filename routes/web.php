<?php

declare(strict_types=1);

use App\Http\Controllers\DraftController;
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
            Route::post('/', 'store')->name('store');
            Route::get('create', 'create')->name('create');
            Route::get('{link}', 'show')
                ->name('show')
                ->can('view', 'link');
        });

    Route::controller(DraftController::class)
        ->prefix('drafts')
        ->name('drafts.')
        ->group(function (): void {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
        });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
