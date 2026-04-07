<?php

namespace App\UseCases;

use App\Models\Article;
use App\ValueObjects\ArticleTitle;
use App\ValueObjects\PublishDate;

class CreateArticleUseCase
{
    public function handle(string $title, ?string $publishDate): void
    {
        $titleObject = ArticleTitle::fromString($title);
        $publishDateObject = $publishDate ? PublishDate::fromString($publishDate) : null;

        Article::draft($titleObject, $publishDateObject)->save();
    }
}
