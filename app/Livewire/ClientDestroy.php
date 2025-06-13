<?php

namespace App\Livewire;

use App\Models\Client;
use Flux\Flux;
use App\Models\Counter;
use Livewire\Component;
use Livewire\Attributes\On;

class ClientDestroy extends Component
{
    public $clientId;

    #[On('destroyClient')]
    public function destroyClient($id)
    {
        $this->clientId = $id;
        Flux::modal('client-destroy')->show();
    }

    public function destroy()
    {
        $client = Client::findOrFail($this->clientId);
        $client->delete();
        session()->flash('success', 'Cliente borrado exitosamente.');
        $this->dispatch('pg:eventRefresh');
        Flux::modal('client-destroy')->close();
        return $this->redirect(route('client.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.client-destroy');
    }
}
