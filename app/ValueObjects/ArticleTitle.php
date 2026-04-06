<?php

namespace App\ValueObjects;

use App\Exceptions\InvalidArticleTitleException;

class ArticleTitle
{
    private function __construct(
        private readonly string $value,
    ) {
    }

    public static function fromString(string $value): self
    {
        $trimmed = trim($value);

        if ($trimmed === '') {
            throw new InvalidArticleTitleException('タイトルは必須です。');
        }

        if (mb_strlen($trimmed) > 255) {
            throw new InvalidArticleTitleException('タイトルは255文字以内で入力してください。');
        }

        return new self($trimmed);
    }

    public function value(): string
    {
        return $this->value;
    }
}
