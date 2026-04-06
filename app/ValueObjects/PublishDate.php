<?php

namespace App\ValueObjects;

use Carbon\CarbonImmutable;

class PublishDate
{
    public function __construct(
        private readonly CarbonImmutable $value,
    ) {}

    public static function fromString(string $value): self
    {
        return new self(CarbonImmutable::parse($value)->startOfDay());
    }

    public function toDateString(): string
    {
        return $this->value->toDateString();
    }
}
