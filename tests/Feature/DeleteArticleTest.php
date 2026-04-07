<?php

use App\Models\Article;
use App\ValueObjects\ArticleTitle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

it('記事を削除できる', function () {
    /** @var TestCase $this */
    $article = Article::draft(ArticleTitle::fromString('削除される記事'), null);
    $article->save();

    $response = $this->delete("/articles/{$article->id}");

    $response->assertRedirect('/articles');

    $this->assertDatabaseMissing('articles', [
        'id' => $article->id,
    ]);
});
