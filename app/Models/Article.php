<?php

namespace App\Models;

use App\Enums\ArticleStatus;
use App\Exceptions\ArticleAlreadyPublishedException;
use App\ValueObjects\ArticleTitle;
use App\ValueObjects\PublishDate;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'status',
        'publish_date',
    ];

    public static function draft(ArticleTitle $title, ?PublishDate $publishDate): self
    {
        return new self([
            'title' => $title->value(),
            'status' => ArticleStatus::Draft,
            'publish_date' => $publishDate?->toDateString(),
        ]);
    }

    protected function casts(): array
    {
        return [
            'publish_date' => 'date',
            'status' => ArticleStatus::class,
        ];
    }

    public function publish(): void
    {
        if ($this->status === ArticleStatus::Published) {
            throw new ArticleAlreadyPublishedException('Article is already published.');
        }

        $this->status = ArticleStatus::Published;
    }
}
