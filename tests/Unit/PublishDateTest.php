<?php

use App\ValueObjects\PublishDate;
use App\Exceptions\PastPublishDateException;

it('文字列から公開予定日を作れる', function () {
    $publishDate = PublishDate::fromString('2026-04-10');

    expect($publishDate->toDateString())->toBe('2026-04-10');
});

it('過去の日付を指定すると例外が投げられる', function () {
    $this->expectException(PastPublishDateException::class);

    PublishDate::fromString('2020-04-10');
});
