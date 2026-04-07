<?php

use App\Models\Article;
use App\ValueObjects\ArticleTitle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

it('記事一覧を表示できる', function () {
    /** @var TestCase $this */
    $oldArticle = Article::draft(ArticleTitle::fromString('古い記事'), null);
    $oldArticle->created_at = now()->subMinute();
    $oldArticle->save();

    $newArticle = Article::draft(ArticleTitle::fromString('新しい記事'), null);
    $newArticle->created_at = now();
    $newArticle->save();

    $response = $this->get('/articles');

    $response->assertOk();
    $response->assertSee('記事一覧');
    $response->assertSeeInOrder(['新しい記事', '古い記事']);
});

it('記事がない場合は空メッセージを表示する', function () {
    /** @var TestCase $this */
    $response = $this->get('/articles');

    $response->assertOk();
    $response->assertSee('記事はまだありません');
});
