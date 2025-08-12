<?php

namespace App\Livewire;

use Flux\Flux;
use App\Models\Counter;
use Livewire\Component;
use Livewire\Attributes\On;

class CounterDestroy extends Component
{
    public $counterId;

    #[On('destroyCounter')]
    public function destroyCounter($id)
    {
        $this->counterId = $id;
        Flux::modal('counter-destroy')->show();
    }

    public function destroy()
    {
        $counter = Counter::findOrFail($this->counterId);
        $counter->delete();
        session()->flash('success', 'Contador borrado exitosamente.');
        $this->dispatch('pg:eventRefresh');
        Flux::modal('counter-destroy')->close();
        return $this->redirect(route('counter.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.counter-destroy');
    }
}
