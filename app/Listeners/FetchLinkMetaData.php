<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Data\LinkMetaData;
use App\Events\LinkCreated;
use App\Events\LinkUpdated;
use App\Exceptions\FailedToFetchLinkMetaData;
use App\Models\Link;
use App\Services\MetaData;
use Illuminate\Support\Collection;

class FetchLinkMetaData
{
    public function handle(LinkCreated|LinkUpdated $event): void
    {
        if ($event instanceof LinkUpdated && ! $event->link->wasChanged('url')) {
            return;
        }

        defer(function () use ($event): void {
            $this->handleDeferred($event->link);
        });
    }

    public function handleDeferred(Link $link): void
    {
        $service = new MetaData($link->url);

        $data = $service->getData();
        if ($data->isEmpty()) {
            return;
        }

        if ($this->isInvalidData($data)) {
            report(new FailedToFetchLinkMetaData($link, $data));

            return;
        }

        $link->update([
            'metadata' => LinkMetaData::from($data),
        ]);
    }

    /**
     * @param  Collection<string, non-empty-string>  $data
     */
    private function isInvalidData(Collection $data): bool
    {
        if ($data->isEmpty()) {
            return true;
        }

        if ($data->get('title') === null && $data->get('description') === null) {
            return true;
        }

        return $data->get('image') === null && $data->get('html') === null;
    }
}
