<?php

namespace App\UseCases;

use App\Models\Article;

class PublishArticleUseCase
{
    public function handle(Article $article): void
    {
        $article->publish();
        $article->save();
    }
}
