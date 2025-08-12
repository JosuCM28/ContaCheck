<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;
use Livewire\Attributes\On;

class TaskActions extends Component
{
    #[On('markAsDone')]
    public function markAsDone(int $id): void
    {
        if ($task = Task::find($id)) {
            if ($task->status !== 'done') {
                $task->status = 'done';
                $task->save();
            }

            $this->dispatch('task-done', id: $id);
        }
    }

    public function render()
    {
        return view('livewire.task-actions');
    }
}
