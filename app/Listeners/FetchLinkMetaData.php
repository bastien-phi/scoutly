<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Actions\FetchLinkMetadata as FetchLinkMetadataAction;
use App\Events\LinkCreated;
use App\Events\LinkUpdated;

class FetchLinkMetaData
{
    public function __construct(
        private readonly FetchLinkMetadataAction $fetchLinkMetadata
    ) {}

    public function handle(LinkCreated|LinkUpdated $event): void
    {
        if ($event instanceof LinkUpdated && ! $event->link->wasChanged('url')) {
            return;
        }

        defer(function () use ($event): void {
            $this->fetchLinkMetadata->execute($event->link);
        });
    }
}
