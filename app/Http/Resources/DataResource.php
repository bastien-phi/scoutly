<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\LaravelData\Data;

class DataResource extends JsonResource
{
    /**
     * @param  class-string<Data>  $dataClass
     */
    public function __construct(
        mixed $resource,
        private string $dataClass
    ) {
        parent::__construct($resource);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->dataClass::from($this->resource)->toArray();
    }
}
