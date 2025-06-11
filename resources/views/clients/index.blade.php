<x-layouts.app :title="__('Clientes')" :subheading="__('Lista de todos los clientes existentes')" >
<div class="relative mb-6 w-full">
    <flux:modal.trigger name="create-counter">
        <flux:button icon="plus" class="mb-4" href="{{route('client.create')}}">Agregar cliente</flux:buttom>
    </flux:modal.trigger>

    <livewire:client-table>
</div>
    


</x-layouts.app>
