<?php

namespace App\Http\Controllers;

use App\Exceptions\ArticleAlreadyPublishedException;
use App\Exceptions\InvalidArticleTitleException;
use App\Exceptions\PastPublishDateException;
use App\Models\Article;
use App\UseCases\CreateArticleUseCase;
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

    public function store(Request $request, CreateArticleUseCase $useCase): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'publish_date' => ['nullable', 'date'],
        ]);

        try {
            $useCase->handle($validated['title'], $validated['publish_date'] ?? null);
        } catch (PastPublishDateException $exception) {
            return back()->withErrors(['publish_date' => $exception->getMessage()]);
        } catch (InvalidArticleTitleException $exception) {
            return back()->withErrors(['title' => $exception->getMessage()]);
        }

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
