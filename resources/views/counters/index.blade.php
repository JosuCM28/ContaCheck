<x-layouts.app :title="__('Contadores')" :subheading="__('Lista de todos los Contadores existentes')">
    <div class="relative mb-6 w-full">
        <livewire:counter-destroy />

        @if (session('success'))
            <div
                x-data="{ show: true }"
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
