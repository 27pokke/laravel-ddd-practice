<?php

use App\Exceptions\InvalidArticleTitleException;
use App\Exceptions\PastPublishDateException;
use App\Models\Article;
use App\UseCases\CreateArticleUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('記事を作成できる', function () {
    $useCase = app()->make(CreateArticleUseCase::class);

    $useCase->handle('テスト記事', now()->addDay()->toDateString());

    expect(Article::count())->toBe(1);
    expect(Article::first()?->title)->toBe('テスト記事');
});

test('空タイトルでは記事を作成できない', function () {
    $useCase = app()->make(CreateArticleUseCase::class);

    expect(fn () => $useCase->handle('', now()->addDay()->toDateString()))
        ->toThrow(InvalidArticleTitleException::class);

    expect(Article::count())->toBe(0);
});

test('過去日の公開予定日では記事を作成できない', function () {
    $useCase = app()->make(CreateArticleUseCase::class);

    expect(fn () => $useCase->handle('テスト記事', now()->subDay()->toDateString()))
        ->toThrow(PastPublishDateException::class);

    expect(Article::count())->toBe(0);
});
