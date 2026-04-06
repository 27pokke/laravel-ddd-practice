<?php

namespace App\ValueObjects;

use App\Exceptions\PastPublishDateException;
use Carbon\CarbonImmutable;

class PublishDate
{
    public function __construct(
        private readonly CarbonImmutable $value,
    ) {}

    public static function fromString(string $value): self
    {
        $date = CarbonImmutable::parse($value);
        if ($date->startOfDay()->lt(today()->startOfDay())) {
            throw new PastPublishDateException('公開予定日は過去の日付を指定できません。');
        }

        return new self($date->startOfDay());
    }

    public function toDateString(): string
    {
        return $this->value->toDateString();
    }
}
