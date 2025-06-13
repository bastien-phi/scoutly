<?php

declare(strict_types=1);

namespace App\Data;

use App\Enums\ToastType;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ToastData extends Data
{
    public function __construct(
        public ToastType $type,
        public string $message,
    ) {}

    public static function info(string $message): self
    {
        return new self(ToastType::Info, $message);
    }

    public static function success(string $message): self
    {
        return new self(ToastType::Success, $message);
    }

    public static function warning(string $message): self
    {
        return new self(ToastType::Warning, $message);
    }

    public static function error(string $message): self
    {
        return new self(ToastType::Error, $message);
    }
}
