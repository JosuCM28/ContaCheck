<x-layouts.app :title="__('Tareas')">
    <div class="flex items-center gap-2 mb-4">
        <flux:heading size="lg" level="1">Tareas</flux:heading>
    </div>
    <flux:subheading class="mb-4">Lista de todas las tareas existentes</flux:subheading>
    <flux:separator variant="subtle" class="mb-4" />

    <livewire:kanban-board />
</x-layouts.app>

