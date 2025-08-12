<x-layouts.app :title="__('Contadores')" :subheading="__('Lista de todos los Contadores existentes')">
    <div class="flex items-center gap-2 mb-4">
        <flux:heading size="lg" level="1">Contadores</flux:heading>
    </div>
        <flux:subheading class="mb-4">Lista de todos los contadores existentes</flux:subheading>
    <flux:separator variant="subtle" class="mb-4" />
    <div class="relative mb-6 w-full">
        <livewire:counter-destroy />

        <flux:modal.trigger name="create-counter">
            <flux:button icon="plus" class="mb-4" href="{{route('counter.create')}}">Agregar contador</flux:buttom> 
        </flux:modal.trigger>
        <livewire:counter-table>
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
