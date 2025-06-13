<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use DirectoryTree\ImapEngine\Laravel\Facades\Imap;
use DirectoryTree\ImapEngine\Message;

class CheckDraftInbox
{
    public function __construct(
        private IngestDraftMessage $ingestDraftMessage
    ) {}

    public function execute(User $user): void
    {
        Imap::mailbox('default')
            ->inbox()
            ->messages()
            ->withHeaders()
            ->withFlags()
            ->withBody()
            ->from($user->email)
            ->each(function (Message $message) use ($user): void {
                $this->ingestDraftMessage->execute($user, $message);
            });
    }
}
