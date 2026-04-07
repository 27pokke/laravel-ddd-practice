<?php

namespace App\UseCases;

use App\Models\Article;

class DeleteArticleUseCase
{
    public function handle(Article $article): void
    {
        $article->delete();
    }
}
