@php
    $client = auth()->user()->client;
@endphp

<x-layouts.epp >
    <flux:heading size="lg" level="1">Mis recibos</flux:heading>

    <flux:subheading class="mb-4">Lista de todos los Recibos existentes</flux:subheading>
    <flux:separator variant="subtle" class="mb-4" />

    <div class="relative mb-6 w-full">
        <livewire:receipt-table :client="$client->id">
    </div>
</x-layouts.app>