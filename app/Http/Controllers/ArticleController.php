<?php

namespace App\Http\Controllers;

use App\Enums\ArticleStatus;
use App\Exceptions\ArticleAlreadyPublishedException;
use App\Models\Article;
use App\UseCases\PublishArticleUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::query()
            ->latest()
            ->get();

        return view('articles.index', [
            'articles' => $articles,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'publish_date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        Article::create([
            'title' => $validated['title'],
            'status' => ArticleStatus::Draft,
            'publish_date' => $validated['publish_date'] ?? null,
        ]);

        return to_route('articles.index');
    }

    public function publish(Article $article, PublishArticleUseCase $useCase): RedirectResponse
    {
        try {
            $useCase->handle($article);
        } catch (ArticleAlreadyPublishedException $exception) {
            abort(400);
        }

        return to_route('articles.index');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return to_route('articles.index');
    }
}
