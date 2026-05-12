<x-layouts.app :title="__('Excel a TXT')">
    {{-- Encabezado superior --}}
    <div class="flex items-center gap-2 mb-4">
        <flux:heading size="lg" level="1">Convertir Excel a TXT</flux:heading>
    </div>

    <flux:subheading class="mb-4">
        Sube un archivo Excel y se convertirá automáticamente a un TXT con datos separados por pipes
    </flux:subheading>

    <flux:separator variant="subtle" class="mb-4" />

    <livewire:excel-to-txt-converter />

</x-layouts.app>
