<div>
    <flux:modal name="client-destroy" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Eliminar cliente</flux:heading>

                <flux:text class="mt-2">
                    <p>Estas seguro de que deseas eliminar el cliente?</p>
                    <p>Esta acción no se puede deshacer.</p>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button wire:click='destroy()' type="submit" class="bg-red-600 hover:bg-red-700 cursor-pointer" variant="primary">Aceptar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
