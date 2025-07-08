<?php

declare(strict_types=1);

use App\Http\Controllers\Settings\AuthorController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\TagController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function (): void {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('settings/appearance', fn () => Inertia::render('settings/appearance'))->name('appearance');

    Route::controller(AuthorController::class)
        ->prefix('settings/authors')
        ->name('settings.authors.')
        ->group(function (): void {
            Route::get('/', 'index')->name('index');
            Route::put('{author}', 'update')
                ->can('update', 'author')
                ->name('update');
            Route::delete('{author}', 'destroy')
                ->can('delete', 'author')
                ->name('destroy');
        });

    Route::controller(TagController::class)
        ->prefix('settings/tags')
        ->name('settings.tags.')
        ->group(function (): void {
            Route::get('/', 'index')->name('index');
            Route::put('{tag}', 'update')
                ->can('update', 'tag')
                ->name('update');
            Route::delete('{tag}', 'destroy')
                ->can('delete', 'tag')
                ->name('destroy');
        });
});
