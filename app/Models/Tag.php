<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\RoutedByUuid;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $label
 * @property \Carbon\CarbonImmutable $created_at
 * @property \Carbon\CarbonImmutable $updated_at
 * @property string $uuid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Link> $links
 * @property-read \App\Models\User $user
 *
 * @mixin \IdeHelper\App\Models\__Tag
 */
class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;

    use RoutedByUuid;

    /**
     * @return BelongsToMany<Link, $this>
     */
    public function links(): BelongsToMany
    {
        return $this->belongsToMany(Link::class);
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
    protected function wherePublic(Builder $query): void
    {
        $query->whereRelation('links', 'is_public', true);
    }
}
