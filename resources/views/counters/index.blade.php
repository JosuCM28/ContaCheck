<x-layouts.app :title="__('Contadores')" :subheading="__('Lista de todos los Contadores existentes')" >
<div class="relative mb-6 w-full">
<flux:modal.trigger name="create-counter">
<flux:button class="mb-4"> Agregar Contador </flux:buttom>
</flux:modal.trigger>

<livewire:counter-table>
</div>
    


</x-layouts.app>
