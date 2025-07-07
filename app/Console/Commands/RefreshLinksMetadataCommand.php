<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\FetchLinkMetadata;
use App\Models\Link;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Prompts\Progress;

use function Laravel\Prompts\progress;

class RefreshLinksMetadataCommand extends Command
{
    /** @var string */
    protected $signature = 'app:refresh-links-metadata {--only-empty}';

    /** @var string */
    protected $description = 'Command description';

    public function handle(FetchLinkMetadata $fetchLinkMetadata): void
    {
        progress(
            label: 'Fetching link metadata',
            steps: Link::query()
                ->when(
                    $this->option('only-empty'),
                    fn (Builder $query) => $query->whereNull('metadata')
                )
                ->lazyById(),
            callback: function (Link $link, Progress $progress) use ($fetchLinkMetadata): void {
                $progress->hint("Fetching metadata for link {$link->url} ...");
                $fetchLinkMetadata->execute($link);
            },
        );
    }
}
