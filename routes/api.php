<?php

declare(strict_types=1);

use App\Http\Controllers\CommunityAuthorController;
use App\Http\Controllers\CommunityTagController;
use App\Http\Controllers\Dashboard\CommunityLinkCountController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->name('api.')->group(function (): void {
    Route::get('authors', [CommunityAuthorController::class, 'index'])
        ->name('community-authors.index');

    Route::get('tags', [CommunityTagController::class, 'index'])
        ->name('community-tags.index');

    Route::name('dashboard.')
        ->prefix('dashboard')
        ->group(function (): void {
            Route::get('community-link-count', CommunityLinkCountController::class)
                ->name('community-link-count');
        });
});
