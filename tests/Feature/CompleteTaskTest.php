<?php

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

it('未完了タスクは完了できる', function () {
    /** @var TestCase $this */
    $task = Task::query()->create([
        'title' => '未完了タスク',
        'status' => 'todo',
        'due_date' => null,
    ]);

    $response = $this->patch("/tasks/{$task->id}/complete");

    $response->assertRedirect('/tasks');

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'status' => 'done',
    ]);
});

it('完了済みタスクは再度完了できない', function () {
    /** @var TestCase $this */
    $task = Task::query()->create([
        'title' => '完了済みタスク',
        'status' => 'done',
        'due_date' => null,
    ]);

    $response = $this->patch("/tasks/{$task->id}/complete");

    $response->assertStatus(400);

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'status' => 'done',
    ]);
});
