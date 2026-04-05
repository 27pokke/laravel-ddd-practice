<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
Route::patch('/articles/{article}/publish', [ArticleController::class, 'publish'])->name('articles.publish');
Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
