<?php

namespace App\UseCases;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\ValueObjects\PublishDate;

class CreateArticleUseCase
{
    public function handle(string $title, ?string $publishDate): void
    {
        $publishDateObject = $publishDate ? PublishDate::fromString($publishDate) : null;
        Article::draft($title, $publishDateObject)->save();
    }
}
