<?php

namespace App\Livewire;

use Flux\Flux;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\Counter;
use Livewire\Component;
use Livewire\Attributes\On;

class KanbanBoard extends Component
{
    public $columns = [
        'todo' => 'Por hacer',
        'in_progress' => 'En progreso',
        'done' => 'Hecho',
    ];

    public $tasks;
    public $editingTask;
    public $newTask = ['title' => '', 'description' => '', 'status' => 'todo', 'counter' => ''];
    public $counters;

    public function mount()
    {
        Carbon::setLocale('es');
        $this->loadTasks();
    }

    public function loadTasks()
    {
        $this->tasks = [];
        foreach (array_keys($this->columns) as $status) {
            $this->tasks[$status] = Task::where('status', $status)
                ->orderBy('position')
                ->orderBy('id')
                ->get()
                ->values();
        }

        $this->counters = Counter::all();
    }

    public function render()
    {
        return view('livewire.kanban-board');
    }

    public function createTask()
    {
        $this->validate([
            'newTask.title'       => 'required|string|max:255',
            'newTask.description' => 'nullable|string',
            'newTask.status'      => 'required|in:' . implode(',', array_keys($this->columns)),
            'newTask.counter'     => 'required|string|max:255',
        ]);

        Task::create([
            'title'        => $this->newTask['title'],
            'description'  => $this->newTask['description'],
            'status'       => $this->newTask['status'],
            'counter_name' => $this->newTask['counter'],
            'position'     => ($this->tasks[$this->newTask['status']]->max('position') ?? -1) + 1,
        ]);

        $this->newTask = ['title' => '', 'description' => '', 'status' => 'todo', 'counter' => ''];

        $this->loadTasks();
        Flux::modal('create-task')->close();
    }

    #[On('deleteTask')]
    public function deleteTask($taskId)
    {
        Task::find($taskId)->delete();
        $this->loadTasks();
        Flux::modal('show-task')->close();
    }

    #[On('updateOrder')]
    public function updateOrder(string $status, array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            Task::where('id', $id)->update([
                'status'   => $status,
                'position' => $index,
            ]);
        }
        $this->loadTasks();
    }
}