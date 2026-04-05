<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'status',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'status' => TaskStatus::class,
        ];
    }

    public function complete(): void
    {
        if ($this->status === TaskStatus::Done) {
            throw new \DomainException('Task is already completed.');
        }

        $this->update([
            'status' => TaskStatus::Done,
        ]);
    }
}
