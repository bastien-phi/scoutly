<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\LaravelData\Data;

class DataCollectionResource extends ResourceCollection
{
    /**
     * @param  class-string<Data>  $dataClass
     */
    public function __construct(
        mixed $resource,
        private readonly string $dataClass
    ) {
        parent::__construct($resource);
    }

    /**
     * @return array<int|string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        return $this->collection
            ->map(fn ($item) => DataResource::make($item, $this->dataClass))
            ->toArray();
    }
}
