<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Models\Link;
use Exception;
use Illuminate\Support\Collection;

class FailedToFetchLinkMetaData extends Exception
{
    public function __construct(
        Link $link,
        Collection $metadata
    ) {
        parent::__construct(strtr(
            'Failed to fetch metadata for link: :link. Found :metadata',
            [
                ':link' => $link->url,
                ':metadata' => $metadata->toJson(),
            ]
        ));
    }
}
