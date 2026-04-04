<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Tasks</title>
</head>
<body>
    <h1>タスク一覧</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('tasks.store') }}" method="POST">
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
            <label for="due_date">期限</label>
            <input
                id="due_date"
                type="date"
                name="due_date"
                value="{{ old('due_date') }}"
            >
        </div>

        <button type="submit">作成</button>
    </form>

    <hr>

<ul>
    @forelse ($tasks as $task)
        <li>
            {{ $task->title }}
            / {{ $task->status }}
            / {{ optional($task->due_date)?->format('Y-m-d') ?? '期限なし' }}

            @if ($task->status !== 'done')
                <form action="{{ route('tasks.complete', $task) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit">完了</button>
                </form>
            @endif

            <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">削除</button>
            </form>
        </li>
    @empty
        <li>タスクはまだありません</li>
    @endforelse
</ul>
</body>
</html>
