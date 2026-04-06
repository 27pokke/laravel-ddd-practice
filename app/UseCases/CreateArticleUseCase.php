<?php

namespace App\UseCases;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\ValueObjects\PublishDate;
use App\ValueObjects\ArticleTitle;

class CreateArticleUseCase
{
    public function handle(string $title, ?string $publishDate): void
    {
        $titleObject = ArticleTitle::fromString($title);
        $publishDateObject = $publishDate ? PublishDate::fromString($publishDate) : null;

        Article::draft($titleObject, $publishDateObject)->save();
    }
}
