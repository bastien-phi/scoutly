<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Http\Requests\Settings\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class PasswordController
{
    public function edit(): Response
    {
        return Inertia::render('settings/password');
    }

    public function update(UpdatePasswordRequest $request, #[CurrentUser] User $user): RedirectResponse
    {
        $user->update([
            'password' => Hash::make($request->validated(['password'])),
        ]);

        return back();
    }
}
