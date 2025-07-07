<?php

declare(strict_types=1);

namespace App\Models;

use App\Data\LinkMetaData;
use App\Models\Concerns\RoutedByUuid;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $author_id
 * @property string|null $title
 * @property string $url
 * @property string|null $description
 * @property \Carbon\CarbonImmutable|null $published_at
 * @property \Carbon\CarbonImmutable $created_at
 * @property \Carbon\CarbonImmutable $updated_at
 * @property bool $is_public
 * @property string $uuid
 * @property bool $fetching_metadata
 * @property \App\Data\LinkMetaData|null $metadata
 * @property-read \App\Models\Author|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read \App\Models\User $user
 *
 * @mixin \IdeHelper\App\Models\__Link
 */
class Link extends Model
{
    /** @use HasFactory<\Database\Factories\LinkFactory> */
    use HasFactory;

    use RoutedByUuid;
    use Searchable;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'metadata' => LinkMetaData::class,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    #[SearchUsingFullText(
        ['title', 'description', 'url'],
        ['language' => 'english', 'mode' => 'fuzzy_websearch']
    )]
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
        ];
    }

    /**
     * @return BelongsTo<Author, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * @return BelongsToMany<Tag, $this>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<$this>  $query
     */
    #[Scope]
    protected function whereDraft(Builder $query): void
    {
        $query->whereNull('published_at');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<$this>  $query
     */
    #[Scope]
    protected function wherePublic(Builder $query): void
    {
        $query->where('is_public', true);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<$this>  $query
     */
    #[Scope]
    protected function wherePublished(Builder $query): void
    {
        $query->whereNotNull('published_at');
    }
}
