<?php

declare(strict_types=1);

use App\Http\Controllers\CheckDraftInboxController;
use App\Http\Controllers\CommunityLinkController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\DraftController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\PublishDraftController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

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
            Route::get('{link}/edit', 'edit')
                ->name('edit')
                ->can('update', 'link');
            Route::put('{link}', 'update')
                ->name('update')
                ->can('update', 'link');
            Route::delete('{link}', 'destroy')
                ->name('destroy')
                ->can('delete', 'link');
        });

    Route::controller(DraftController::class)
        ->prefix('drafts')
        ->name('drafts.')
        ->group(function (): void {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('{draft}/edit', 'edit')
                ->name('edit')
                ->can('update-draft', 'draft');
            Route::put('{draft}', 'update')
                ->name('update')
                ->can('update-draft', 'draft');
        });

    Route::post('drafts/check-inbox', CheckDraftInboxController::class)
        ->name('drafts.check-inbox');

    Route::put('drafts/{draft}/publish', PublishDraftController::class)
        ->name('drafts.publish')
        ->can('update-draft', 'draft');

    Route::get('community-links', [CommunityLinkController::class, 'index'])
        ->name('community-links.index');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
