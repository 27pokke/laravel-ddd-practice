<?php

namespace App\UseCases;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\ValueObjects\PublishDate;

class CreateArticleUseCase
{
    public function handle(string $title, ?string $publishDate): void
    {
        $publishDateValue = $publishDate
            ? PublishDate::fromString($publishDate)->toDateString()
            : null;

        Article::create([
            'title' => $title,
            'status' => ArticleStatus::Draft,
            'publish_date' => $publishDateValue,
        ]);
    }
}
