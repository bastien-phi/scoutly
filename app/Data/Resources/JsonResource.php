<?php

declare(strict_types=1);

namespace App\Data\Resources;

use Illuminate\Contracts\Pagination\CursorPaginator as CursorPaginatorContract;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\LazyCollection;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\LaravelData\Resource;

class JsonResource extends Resource
{
    /**
     * @param  resource|Collection<array-key, resource>|EloquentCollection<array-key, resource>|LazyCollection<array-key, resource>|Enumerable<array-key, resource>|array<array-key, resource>|AbstractPaginator<array-key, resource>|PaginatorContract<array-key, resource>|AbstractCursorPaginator<array-key, resource>|CursorPaginatorContract<array-key, resource>|DataCollection<array-key, resource>  $data
     */
    public function __construct(
        public Resource|array|DataCollection|PaginatedDataCollection|CursorPaginatedDataCollection|Enumerable|AbstractPaginator|PaginatorContract|AbstractCursorPaginator|CursorPaginatorContract|LazyCollection|Collection $data
    ) {}

    public static function make(Resource $resource): self
    {
        return new self(data: $resource);
    }

    /**
     * @static
     *
     * @param  Collection<array-key, mixed>|EloquentCollection<array-key, mixed>|LazyCollection<array-key, mixed>|Enumerable<array-key, mixed>|array<array-key, mixed>|AbstractPaginator<array-key, mixed>|PaginatorContract<array-key, mixed>|AbstractCursorPaginator<array-key, mixed>|CursorPaginatorContract<array-key, mixed>|DataCollection<array-key, mixed>  $items
     * @param  class-string<resource>  $resource
     */
    public static function collection(
        array|DataCollection|PaginatedDataCollection|CursorPaginatedDataCollection|Enumerable|AbstractPaginator|PaginatorContract|AbstractCursorPaginator|CursorPaginatorContract|LazyCollection|Collection $items,
        string $resource
    ): self {
        return new self(data: $resource::collect($items));
    }
}
