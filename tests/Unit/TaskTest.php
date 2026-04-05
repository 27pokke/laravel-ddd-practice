<?php

use App\Enums\TaskStatus;
use App\Models\Task;

it('未完了タスクは完了できる', function () {
    $task = new Task([
        'title' => '未完了タスク',
        'status' => TaskStatus::Todo,
        'due_date' => null,
    ]);

    $task->complete();

    expect($task->status)->toBe(TaskStatus::Done);
});

it('完了済みタスクは再度完了できない', function () {
    $task = new Task([
        'title' => '完了済みタスク',
        'status' => TaskStatus::Done,
        'due_date' => null,
    ]);

    expect(fn () => $task->complete())
        ->toThrow(DomainException::class);
});
