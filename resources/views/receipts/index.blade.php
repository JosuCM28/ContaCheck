<x-layouts.app :title="__('Recibos')" :subheading="__('Lista de todos los Recibos existentes')" >
    <div class="flex items-center gap-2 mb-4">
        <flux:heading size="lg" level="1">Recibos</flux:heading>
    </div>
        <flux:subheading class="mb-4">Lista de todos los recibos existentes</flux:subheading>
    <flux:separator variant="subtle" class="mb-4" />
    <div class="relative mb-6 w-full">
        @if (session('success'))
            <div
                x-data="{ show: true 
                x-init="setTimeout(() => show = false, 3000)"
                x-show="show"
                x-transition
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4"
                role="alert"
            >
                {{ session('success') }}
            </div>
        @endif
        
        <flux:modal.trigger name="create-counter">
            <flux:button icon="plus" class="mb-4" href="{{route('receipt.create')}}"> Agregar recibo </flux:buttom>
        </flux:modal.trigger>

        <livewire:receipt-table>
    </div>
</x-layouts.app>