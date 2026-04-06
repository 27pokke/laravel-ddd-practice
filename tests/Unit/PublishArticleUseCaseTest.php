<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('記事を公開できる', function () {
    $article = App\Models\Article::draft(App\ValueObjects\ArticleTitle::fromString('テスト記事'), null);
    $article->save();

    $useCase = app()->make(App\UseCases\PublishArticleUseCase::class);
    $useCase->handle($article);

    expect($article->status)->toBe(App\Enums\ArticleStatus::Published);
});

test('公開済み記事は再度公開できない', function () {
    $article = new App\Models\Article([
        'title' => '公開済み記事',
        'status' => App\Enums\ArticleStatus::Published->value,
        'publish_date' => null,
    ]);
    $article->save();

    $useCase = app()->make(App\UseCases\PublishArticleUseCase::class);

    expect(fn () => $useCase->handle($article))
        ->toThrow(App\Exceptions\ArticleAlreadyPublishedException::class);
});
