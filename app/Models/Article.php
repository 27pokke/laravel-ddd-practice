<?php

namespace App\Models;

use App\Enums\ArticleStatus;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'status',
        'publish_date',
    ];

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
            throw new \DomainException('Article is already published.');
        }

        $this->status = ArticleStatus::Published;
    }
}
