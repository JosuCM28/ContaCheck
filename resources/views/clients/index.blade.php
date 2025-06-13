<x-layouts.app :title="__('Clientes')" :subheading="__('Lista de todos los clientes existentes')" >
<div class="relative mb-6 w-full">
<livewire:client-destroy />


@if (session('success'))
    <div id="success-message">
        <flux:callout variant="success" icon="check-circle" heading="{{ session('success') }}" />
    </div>
    
@endif
    <flux:modal.trigger name="create-counter">
        <flux:button icon="plus" class="mb-4" href="{{route('client.create')}}">Agregar cliente</flux:buttom>
    </flux:modal.trigger>

    <livewire:client-table>
</div>
    
<script>
        setTimeout(function() {
            var successDiv = document.getElementById('success-message');
            if (successDiv) {
                successDiv.classList.add('fade-out');
                successDiv.addEventListener('transitionend', function() {
                    successDiv.style.display = 'none';
                });
            }
        }, 3000);
    </script>

</x-layouts.app>
