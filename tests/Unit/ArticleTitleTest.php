<?php

use App\ValueObjects\ArticleTitle;
use App\Exceptions\InvalidArticleTitleException;

test('文字列から記事タイトルを作成できる', function () {
    $title = ArticleTitle::fromString('テスト記事');

    expect($title->value())->toBe('テスト記事');
});

test('前後の空白がトリムされる', function () {
    $title = ArticleTitle::fromString('  テスト記事  ');

    expect($title->value())->toBe('テスト記事');
});

test('空文字列から記事タイトルを作成しようとすると例外が投げられる', function () {
    ArticleTitle::fromString('');
})->throws(InvalidArticleTitleException::class);

test('255文字を超える文字列から記事タイトルを作成しようとすると例外が投げられる', function () {
    $longTitle = str_repeat('あ', 256);
    ArticleTitle::fromString($longTitle);
})->throws(InvalidArticleTitleException::class);
