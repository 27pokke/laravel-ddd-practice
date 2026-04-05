<?php

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

it('今日の公開予定日なら記事を投稿できる', function () {
    /** @var TestCase $this */
    $response = $this->post('/articles', [
        'title' => 'Today article',
        'publish_date' => now()->toDateString(),
    ]);

    $response->assertRedirect('/articles');

    $this->assertDatabaseHas('articles', [
        'title' => 'Today article',
        'status' => 'draft',
    ]);

    expect(Article::query()->sole()->publish_date?->toDateString())->toBe(now()->toDateString());
});

it('公開予定日なしでも記事を投稿できる', function () {
    /** @var TestCase $this */
    $response = $this->post('/articles', [
        'title' => '公開予定日がない記事',
        'publish_date' => null,
    ]);

    $response->assertRedirect('/articles');

    $this->assertDatabaseHas('articles', [
        'title' => '公開予定日がない記事',
        'status' => 'draft',
        'publish_date' => null,
    ]);
});

it('過去日付の公開予定日では記事を投稿できない', function () {
    /** @var TestCase $this */
    $response = $this->post('/articles', [
        'title' => 'Old article',
        'publish_date' => now()->subDay()->toDateString(),
    ]);

    $response->assertSessionHasErrors(['publish_date']);

    $this->assertDatabaseEmpty('articles');
});

it('タイトルが空なら記事を投稿できない', function () {
    /** @var TestCase $this */
    $response = $this->post('/articles', [
        'title' => '',
        'publish_date' => null,
    ]);

    $response->assertSessionHasErrors(['title']);

    $this->assertDatabaseEmpty('articles');
});
