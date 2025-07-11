<x-layouts.app :title="__('Ver Recibo')" :subheading="__('Detalles del recibo')">
    <div class="container mx-auto p-14">
        <div class="space-y-12">
            @if (session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                    class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Tipo de Recibo -->
                <flux:field>
                    <flux:label>Tipo de Recibo</flux:label>
                    <flux:input type="text" readonly value="{{ $receipt->category->name }}"
                        class="w-full bg-gray-100" />
                </flux:field>

                <!-- Realizado Por -->
                <flux:field>
                    <flux:label>Realizado Por</flux:label>
                    <flux:input type="text" readonly value="{{ $receipt->counter->full_name }}"
                        class="w-full bg-gray-100" />
                </flux:field>

                <!-- Contribuyente -->
                <flux:field>
                    <flux:label>Contribuyente</flux:label>
                    <flux:input type="text" readonly value="{{ $receipt->client->full_name }}"
                        class="w-full bg-gray-100" />
                </flux:field>

                <!-- Método de Pago -->
                <flux:field>
                    <flux:label>Método de Pago</flux:label>
                    <flux:input type="text" readonly value="{{ $receipt->pay_method }}" class="w-full bg-gray-100" />
                </flux:field>

                <!-- Monto $MXN -->
                <flux:field>
                    <flux:label>Monto $MXN</flux:label>
                    <flux:input type="text" readonly value="{{ number_format($receipt->mount, 2) }}"
                        class="w-full bg-gray-100" />
                </flux:field>

                <!-- Concepto -->
                <flux:field>
                    <flux:label>Concepto</flux:label>
                    <flux:input type="text" readonly value="{{ $receipt->concept }}" class="w-full bg-gray-100" />
                </flux:field>

                <!-- Fecha de Pago -->
                <flux:field>
                    <flux:label>Fecha de Pago</flux:label>
                    <flux:input type="text" readonly value="{{ old('payment_date', $receipt->payment_date) }}"
                        class="w-full bg-gray-100" />
                </flux:field>

                <!-- Estado -->
                <flux:field>
                    <flux:label>Estado</flux:label>
                    <flux:input type="text" readonly value="{{ $receipt->status }}" class="w-full bg-gray-100" />
                </flux:field>

                <!-- Identificador -->
                <flux:field>
                    <flux:label>Identificador</flux:label>
                    <flux:input type="text" readonly value="{{ $receipt->identificator }}"
                        class="w-full bg-gray-100" />
                </flux:field>
            </div>

            <!-- Acciones -->
            <div class="mt-8 flex justify-end space-x-4">
                <flux:field>
                    <flux:button>
                        <a href="{{ url()->previous() }}" class="btn btn-soft btn-secondary">Cancelar</a>
                    </flux:button>
                </flux:field>
                @if ($receipt->status == 'PENDIENTE')
                    <flux:field>
                        <flux:button variant="primary">
                            <a href="{{ route('receipt.edit', $receipt->id) }}"
                                class="btn btn-soft btn-accent">Editar</a>
                        </flux:button>
                    </flux:field>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
