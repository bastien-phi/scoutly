<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use DirectoryTree\ImapEngine\Laravel\Facades\Imap;
use DirectoryTree\ImapEngine\Message;
use Illuminate\Support\Collection;

class CheckDraftInbox
{
    public function __construct(
        private readonly IngestDraftMessage $ingestDraftMessage
    ) {}

    public function execute(User $user): int
    {
        $drafts = new Collection;

        Imap::mailbox('default')
            ->inbox()
            ->messages()
            ->withHeaders()
            ->withFlags()
            ->withBody()
            ->from($user->email)
            ->each(function (Message $message) use ($user, $drafts): void {
                $drafts->push($this->ingestDraftMessage->execute($user, $message));
            });

        return $drafts->filter()->count();
    }
}
