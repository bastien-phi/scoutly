<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Link;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LinkCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Link $link
    ) {}
}
