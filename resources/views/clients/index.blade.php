<x-layouts.app :title="__('Clientes')" :subheading="__('Lista de todos los clientes existentes')">
    <div class="relative mb-6 w-full">
        <livewire:client-destroy />
        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <flux:modal.trigger name="create-counter">
            <flux:button icon="plus" class="mb-4" href="{{ route('client.create') }}">Agregar cliente</flux:buttom>
        </flux:modal.trigger>

        <livewire:client-table>
    </div>
</x-layouts.app>
