<x-layouts.app :title="__('Convertir CFDI')">
    {{-- Encabezado superior --}}
    <div class="flex items-center gap-2 mb-4">
        <flux:heading size="lg" level="1">Exportar CFDIs a Excel</flux:heading>
    </div>

    <flux:subheading class="mb-4">
        Sube los CFDIs que desea convertir a Excel
    </flux:subheading>

    <flux:separator variant="subtle" class="mb-4" />

    <livewire:cfdi-excel-converter />
    
</x-layouts.app>