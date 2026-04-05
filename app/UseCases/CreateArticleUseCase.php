<?php

namespace App\UseCases;

use App\Enums\ArticleStatus;
use App\Models\Article;

class CreateArticleUseCase
{
    public function handle(string $title, ?string $publishDate): void
    {
        Article::create([
            'title' => $title,
            'status' => ArticleStatus::Draft,
            'publish_date' => $publishDate,
        ]);
    }
}
