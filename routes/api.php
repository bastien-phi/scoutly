<?php

declare(strict_types=1);

use App\Http\Controllers\CommunityAuthorController;
use App\Http\Controllers\CommunityTagController;
use App\Http\Controllers\CommunityUserController;
use App\Http\Controllers\Dashboard\CommunityLinkCountController;
use App\Http\Controllers\Dashboard\CommunityTrendingTagsController;
use App\Http\Controllers\Dashboard\FavoriteTagsController;
use App\Http\Controllers\Dashboard\LinkCountController;
use App\Http\Controllers\Dashboard\RandomCommunityLinkController;
use App\Http\Controllers\Dashboard\RandomLinkController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->name('api.')->group(function (): void {
    Route::get('authors', [CommunityAuthorController::class, 'index'])
        ->name('community-authors.index');

    Route::get('tags', [CommunityTagController::class, 'index'])
        ->name('community-tags.index');

    Route::get('users', [CommunityUserController::class, 'index'])
        ->name('community-users.index');

    Route::name('dashboard.')
        ->prefix('dashboard')
        ->group(function (): void {
            Route::get('community-link-count', CommunityLinkCountController::class)
                ->name('community-link-count');

            Route::get('community-trending-tags', CommunityTrendingTagsController::class)
                ->name('community-trending-tags');

            Route::get('favorite-tags', FavoriteTagsController::class)
                ->name('favorite-tags');

            Route::get('link-count', LinkCountController::class)
                ->name('link-count');

            Route::get('random-community-link', RandomCommunityLinkController::class)
                ->name('random-community-link');

            Route::get('random-link', RandomLinkController::class)
                ->name('random-link');
        });
});
