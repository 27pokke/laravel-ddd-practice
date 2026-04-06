<?php

namespace App\UseCases;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

class ListArticlesUseCase
{
    public function handle(): Collection
    {
        return Article::query()
            ->latest()
            ->get();
    }
}
