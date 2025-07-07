<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\LinkMetaData;
use App\Exceptions\FailedToFetchLinkMetaData;
use App\Models\Link;
use App\Services\MetaData;
use Illuminate\Support\Collection;

class FetchLinkMetadata
{
    public function execute(Link $link): void
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
