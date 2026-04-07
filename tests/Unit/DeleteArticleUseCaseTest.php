<?php

use App\Models\Article;
use App\UseCases\DeleteArticleUseCase;
use App\ValueObjects\ArticleTitle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('記事を削除できる', function () {
    $article = Article::draft(ArticleTitle::fromString('削除される記事'), null);
    $article->save();

    $useCase = app()->make(DeleteArticleUseCase::class);
    $useCase->handle($article);

    expect(Article::count())->toBe(0);
});
