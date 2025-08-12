<x-layouts.app :title="__('Firmas')" :subheading="__('Fechas de vencimiento (FIEL)')">
    <div class="flex items-center gap-2 mb-4">
        <flux:heading size="lg" level="1">FIEL</flux:heading>
    </div>
    
    <flux:subheading class="mb-4">Fechas de vencimiento (FIEL)</flux:subheading>
    <flux:separator variant="subtle" class="mb-4" />
    
    <div class="relative mb-6 w-full">
        {{-- @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                {{ session('success') }}
            </div>
        @endif --}}

        {{-- Botón o acción aquí si se requiere en el futuro --}}
        {{-- <flux:modal.trigger name="create-fiel">
            <flux:button icon="plus" class="mb-4" href="{{ route('fiel.create') }}">Agregar FIEL</flux:button>
        </flux:modal.trigger> --}}

        <livewire:fiel-table />
    </div>
</x-layouts.app>
