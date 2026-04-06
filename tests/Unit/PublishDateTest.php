<?php

use App\ValueObjects\PublishDate;

it('文字列から公開予定日を作れる', function () {
    $publishDate = PublishDate::fromString('2026-04-10');

    expect($publishDate->toDateString())->toBe('2026-04-10');
});
