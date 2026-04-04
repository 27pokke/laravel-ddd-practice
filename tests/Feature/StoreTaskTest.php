<?php

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('今日の日付の期限ならタスクを作成できる', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->post('/tasks', [
        'title' => 'Today task',
        'due_date' => now()->toDateString(),
    ]);

    $response->assertRedirect('/tasks');

    $this->assertDatabaseHas('tasks', [
        'title' => 'Today task',
        'status' => 'todo',
    ]);

    expect(Task::query()->sole()->due_date?->toDateString())->toBe(now()->toDateString());
});

it('期限日なしでもタスクを作成できる', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->post('/tasks', [
        'title' => '期限日がないタスク',
        'due_date' => null,
    ]);

    $response->assertRedirect('/tasks');

    $this->assertDatabaseHas('tasks', [
        'title' => '期限日がないタスク',
        'status' => 'todo',
        'due_date' => null,
    ]);
});

it('過去日付の期限ではタスクを作成できない', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->post('/tasks', [
        'title' => 'Old task',
        'due_date' => now()->subDay()->toDateString(),
    ]);

    $response->assertSessionHasErrors(['due_date']);

    $this->assertDatabaseEmpty('tasks');
});

it('タイトルが空なら作成できない', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->post('/tasks', [
        'title' => '',
        'due_date' => null,
    ]);

    $response->assertSessionHasErrors(['title']);

    $this->assertDatabaseEmpty('tasks');
});
