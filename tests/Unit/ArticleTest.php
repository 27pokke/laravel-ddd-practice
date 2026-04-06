<?php

use App\Enums\ArticleStatus;
use App\Exceptions\ArticleAlreadyPublishedException;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('下書き記事は公開できる', function () {
    $article = new Article([
        'title' => '下書き記事',
        'status' => ArticleStatus::Draft->value,
        'publish_date' => null,
    ]);

    $article->publish();

    expect($article->status)->toBe(ArticleStatus::Published);
});

it('公開済み記事は再度公開できない', function () {
    $article = new Article([
        'title' => '公開済み記事',
        'status' => ArticleStatus::Published->value,
        'publish_date' => null,
    ]);

    expect(fn () => $article->publish())
        ->toThrow(ArticleAlreadyPublishedException::class);
});
