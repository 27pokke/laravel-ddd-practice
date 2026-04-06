<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('記事を作成できる', function () {
    $useCase = app()->make(App\UseCases\CreateArticleUseCase::class);

    $useCase->handle('テスト記事', now()->addDay()->toString());

    expect(App\Models\Article::count())->toBe(1);
    expect(App\Models\Article::first()->title)->toBe('テスト記事');
});
