<?php

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

it('下書き記事は公開できる', function () {
    /** @var TestCase $this */
    $article = Article::query()->create([
        'title' => '下書き記事',
        'status' => 'draft',
        'publish_date' => null,
    ]);

    $response = $this->patch("/articles/{$article->id}/publish");

    $response->assertRedirect('/articles');

    $this->assertDatabaseHas('articles', [
        'id' => $article->id,
        'status' => 'published',
    ]);
});

it('公開済み記事は再度公開できない', function () {
    /** @var TestCase $this */
    $article = Article::query()->create([
        'title' => '公開済み記事',
        'status' => 'published',
        'publish_date' => null,
    ]);

    $response = $this->patch("/articles/{$article->id}/publish");

    $response->assertStatus(400);

    $this->assertDatabaseHas('articles', [
        'id' => $article->id,
        'status' => 'published',
    ]);
});
