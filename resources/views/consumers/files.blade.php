@php
    $client = auth()->user()->client;
@endphp

<x-layouts.epp >
    <flux:heading size="lg" level="1">Mis documentos</flux:heading>

    <flux:subheading class="mb-4">Lista de todos los documentos existentes</flux:subheading>
    <flux:separator variant="subtle" class="mb-4" />

    <flux:modal.trigger name="document-store">
        <flux:button icon="plus" class="mb-4">Agregar Documento</flux:buttom>
    </flux:modal.trigger>

    <livewire:document-table :client="$client" />

    <livewire:document-store />

    <flux:modal name="document-store" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Documentos</flux:heading>
                <flux:text class="mt-2">Documentos asociados a este cliente</flux:text>
            </div>
            <form action="{{ route('file.store', $client->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <flux:input label="Documento" placeholder="Nombre del documento" type="text" name="title" oninput="this.value = this.value.toUpperCase();" required />
                    @error('title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <flux:input type="file" label="Archivo" accept="application/pdf" name="file_path" required />
                    @error('file_path')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="flex mt-4">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary" class="cursor-pointer">Subir documento</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

</x-layouts.app>