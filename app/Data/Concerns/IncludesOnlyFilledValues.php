<?php

declare(strict_types=1);

namespace App\Data\Concerns;

trait IncludesOnlyFilledValues
{
    /**
     * @return array<string, mixed>
     */
    public function onlyNotNull(): array
    {
        return array_filter(
            $this->toArray(),
            fn (mixed $value): bool => filled($value)
        );
    }
}
