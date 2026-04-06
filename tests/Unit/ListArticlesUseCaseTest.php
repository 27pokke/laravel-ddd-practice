<?php

use App\Models\Article;
use App\UseCases\ListArticlesUseCase;
use App\ValueObjects\ArticleTitle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('記事一覧を新しい順で取得できる', function () {
    $oldArticle = Article::draft(ArticleTitle::fromString('古い記事'), null);
    $oldArticle->created_at = now()->subMinute();
    $oldArticle->save();

    $newArticle = Article::draft(ArticleTitle::fromString('新しい記事'), null);
    $newArticle->created_at = now();
    $newArticle->save();

    $useCase = app()->make(ListArticlesUseCase::class);

    $articles = $useCase->handle();

    expect($articles)->toHaveCount(2);
    expect($articles->first()->title)->toBe('新しい記事');
});
