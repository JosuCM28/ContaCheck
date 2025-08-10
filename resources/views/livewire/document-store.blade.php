<div>
    <flux:modal name="document-destroy" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Eliminar documento</flux:heading>

                <flux:text class="mt-2">
                    <p>Estas seguro de que deseas eliminar el documento?</p>
                    <p>Esta acción no se puede deshacer.</p>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button wire:click='destroy()' type="submit" class="bg-red-600 hover:bg-red-700 cursor-pointer" id="deleteButton" variant="primary">
                    <flux:icon.loading class="size-4 hidden" id="deleteButtonIcon" />
                    <span id="deleteButtonText">Eliminar</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>

<script>
    const deleteButton = document.getElementById('deleteButton');
    deleteButton.addEventListener('click', function() {
        deleteButton.disabled = true;
        deleteButton.classList.add('opacity-50');
        document.getElementById('deleteButtonText').classList.add('hidden');
        document.getElementById('deleteButtonIcon').classList.remove('hidden');
    });
</script>
