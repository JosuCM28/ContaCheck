<x-layouts.app :title="__('Contadores')" :subheading="__('Lista de todos los Contadores existentes')">
    <div class="relative mb-6 w-full">
        <livewire:counter-destroy />
        @if (session('success'))
            <div id="success-message">
                <flux:callout variant="success" icon="check-circle" heading="{{ session('success') }}" />
            </div>
        @endif

        <flux:modal.trigger name="create-counter">
            <flux:button icon="plus" class="mb-4" href="{{ route('counter.create') }}"> Agregar contador
                </flux:buttom>
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
