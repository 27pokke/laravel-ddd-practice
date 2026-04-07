<?php

namespace App\Http\Controllers;

use App\Exceptions\ArticleAlreadyPublishedException;
use App\Exceptions\InvalidArticleTitleException;
use App\Exceptions\PastPublishDateException;
use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;
use App\UseCases\CreateArticleUseCase;
use App\UseCases\DeleteArticleUseCase;
use App\UseCases\ListArticlesUseCase;
use App\UseCases\PublishArticleUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(ListArticlesUseCase $useCase): View
    {
        return view('articles.index', [
            'articles' => $useCase->handle(),
        ]);
    }

    public function store(StoreArticleRequest $request, CreateArticleUseCase $useCase): RedirectResponse
    {
        try {
            $useCase->handle(
                (string) $request->input('title', ''),
                $request->input('publish_date')
            );
        } catch (PastPublishDateException $exception) {
            return back()
                ->withInput()
                ->withErrors(['publish_date' => $exception->getMessage()]);
        } catch (InvalidArticleTitleException $exception) {
            return back()
                ->withInput()
                ->withErrors(['title' => $exception->getMessage()]);
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

    public function destroy(Article $article, DeleteArticleUseCase $useCase): RedirectResponse
    {
        $useCase->handle($article);

        return to_route('articles.index');
    }
}
