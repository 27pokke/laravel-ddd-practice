<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Articles</title>
</head>
<body>
    @php
        $statusLabels = [
            \App\Enums\ArticleStatus::Draft->value => '下書き',
            \App\Enums\ArticleStatus::Published->value => '公開済み',
        ];
    @endphp

    <h1>記事一覧</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('articles.store') }}" method="POST">
        @csrf

        <div>
            <label for="title">タイトル</label>
            <input
                id="title"
                type="text"
                name="title"
                value="{{ old('title') }}"
            >
        </div>

        <div>
            <label for="publish_date">公開予定日</label>
            <input
                id="publish_date"
                type="date"
                name="publish_date"
                value="{{ old('publish_date') }}"
            >
        </div>

        <button type="submit">投稿する</button>
    </form>

    <hr>

<ul>
    @forelse ($articles as $article)
        <li>
            {{ $article->title }}
            / {{ $statusLabels[$article->status->value] ?? $article->status->value }}
            / {{ optional($article->publish_date)?->format('Y-m-d') ?? '公開予定日なし' }}

            @if ($article->status !== \App\Enums\ArticleStatus::Published)
                <form action="{{ route('articles.publish', $article) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit">公開</button>
                </form>
            @endif

            <form action="{{ route('articles.destroy', $article) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">削除</button>
            </form>
        </li>
    @empty
        <li>記事はまだありません</li>
    @endforelse
</ul>
</body>
</html>
