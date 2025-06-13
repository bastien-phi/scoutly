<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CheckDraftInbox;
use App\Data\ToastData;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class CheckDraftInboxController
{
    public function __invoke(
        #[CurrentUser] User $user,
        CheckDraftInbox $checkDraftInbox,
    ): RedirectResponse {
        $newDraftCount = $checkDraftInbox->execute($user);

        $toast = $newDraftCount === 0
            ? ToastData::info('No new drafts found.')
            : ToastData::success('Successfully imported '.$newDraftCount.' new '.Str::plural('draft', $newDraftCount).'.');

        return redirect()
            ->back()
            ->with([
                'toast' => $toast,
            ]);
    }
}
