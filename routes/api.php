<?php

declare(strict_types=1);

use App\Http\Controllers\CommunityAuthorController;
use App\Http\Controllers\CommunityTagController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->name('api.')
    ->group(function (): void {
        Route::get('authors', [CommunityAuthorController::class, 'index'])
            ->name('community-authors.index');

        Route::get('tags', [CommunityTagController::class, 'index'])
            ->name('community-tags.index');
    });
