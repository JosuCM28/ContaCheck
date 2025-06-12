<x-layouts.app :title="__('Recibos')" :subheading="__('Lista de todos los Recibos existentes')" >
    <div class="relative mb-6 w-full">
        <flux:modal.trigger name="create-counter">
            <flux:button icon="plus" class="mb-4" href="{{route('receipt.create')}}"> Agregar recibo </flux:buttom>
        </flux:modal.trigger>

        <livewire:receipt-table>
    </div>
</x-layouts.app>