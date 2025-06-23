<?php

namespace App\Livewire;

use Flux\Flux;
use Livewire\Component;
use App\Models\Document;
use Livewire\Attributes\On;

class DocumentStore extends Component
{
    public $documentId;
    
    #[On('destroyDocument')]
    public function destroyDocument($id)
    {
        $this->documentId = $id;
        Flux::modal('document-destroy')->show();
    }

    public function destroy()
    {
        $document = Document::findOrFail($this->documentId);
        $document->delete();
        $this->dispatch('pg:eventRefresh');
        Flux::modal('document-destroy')->close();
    }
    
    public function render()
    {
        return view('livewire.document-store');
    }
}
