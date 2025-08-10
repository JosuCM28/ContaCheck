<div>
    <flux:modal name="counter-destroy" class="min-w-[22rem]" id="modalDestroy">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Eliminar contador</flux:heading>

                <flux:text class="mt-2">
                    <p>Estas seguro de que deseas eliminar el contador?</p>
                    <p>Esta acción no se puede deshacer.</p>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button wire:click='destroy()' type="submit" class="bg-red-600 hover:bg-red-700 cursor-pointer" variant="primary" id="modalDestroyButton">
                    <flux:icon.loading class="size-4 hidden" id="modalDestroyButtonIcon" />
                    <span id="modalDestroyButtonText">Eliminar contador</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>

<script>
    const btnDestroy = document.getElementById('modalDestroyButton');
    btnDestroy.addEventListener('click', function() {
        btnDestroy.disabled = true;
        btnDestroy.classList.add('opacity-50');
        document.getElementById('modalDestroyButtonText').classList.add('hidden');
        document.getElementById('modalDestroyButtonIcon').classList.remove('hidden');
    });
</script>
