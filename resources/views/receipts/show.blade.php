<x-layouts.app :title="__('Ver Recibo')" :subheading="__('Detalles del recibo')">
    <div class="container mx-auto p-12 max-w-7xl">
        <!-- Encabezado -->
        <div class="text-center space-y-1 mb-10">
            <p class="text-2xl font-bold">
                Recibo #{{ $receipt->identificator }}
            </p>
            <p class="text-sm text-gray-500">
                Detalles del recibo
            </p>
        </div>

        <flux:separator variant="subtle" class="mb-4" />

        <div class="p-8">
            <h2 class="text-lg font-semibold text-gray-800">Información del recibo</h2>

            <div class="my-10 grid grid-cols-1 sm:grid-cols-6 lg:grid-cols-9 gap-6">
                <!-- Tipo de recibo -->
                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Tipo de Recibo</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $receipt->category?->name ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $receipt->category?->name ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <!-- Realizado por -->
                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Realizado por</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $receipt->counter?->full_name ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $receipt->counter?->full_name ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <!-- Contribuyente -->
                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Contribuyente</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $receipt->client?->full_name ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $receipt->client?->full_name ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <!-- Método de pago -->
                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Método de pago</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $receipt->pay_method ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $receipt->pay_method ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <!-- Monto $MXN -->
                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Monto $MXN</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ isset($receipt->mount) ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ isset($receipt->mount) ? number_format((float)$receipt->mount, 2) : 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <!-- Concepto -->
                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Concepto</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $receipt->concept ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $receipt->concept ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <!-- Fecha de pago -->
                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Fecha de pago</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $receipt->payment_date ? '' : 'text-gray-400 italic' }} text-sm">
                            {{-- Usa cast datetime en el modelo: $casts=['payment_date'=>'datetime'] --}}
                            {{ $receipt->payment_date ? ($receipt->payment_date instanceof \Carbon\Carbon
                                ? $receipt->payment_date->format('d/m/Y')
                                : \Carbon\Carbon::parse($receipt->payment_date)->format('d/m/Y'))
                                : 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <!-- Estado -->
                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Estado</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $receipt->status ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $receipt->status ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <!-- FacturaFiel -->
                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label class="block text-sm font-medium leading-6 text-gray-900">FacturaFiel</label>
                    <div class="mt-2 input input-filled peer flex items-center gap-4">
                        <p class="text-sm">
                            {{ $receipt->is_timbred ? 'Timbrado' : 'Sin timbrar' }}
                        </p>
                        @if ($receipt->status === 'PAGADO' && !$receipt->is_timbred && $receipt->category?->name === 'HONORARIOS')
                            <a href="{{ route('timbrar.recibo', $receipt->id) }}"
                               onclick="return confirm('Usted va a timbrar este recibo')"
                               title="Timbrar recibo"
                            >
                                <flux:icon.bell-alert variant="solid" class="hover:text-yellow-300 text-yellow-500 cursor-pointer" />
                            </a>
                        @endif
                    </div>
                </div>

                @if (!empty($receipt->uuid))
                    <!-- Folio UUID (SAT) -->
                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Folio UUID (SAT)</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="text-sm">{{ $receipt->uuid }}</p>
                        </div>
                    </div>
                @endif

                <!-- Identificador -->
                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Identificador</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="text-sm">{{ $receipt->identificator }}</p>
                    </div>
                </div>
            </div>

            <div class="border-b border-gray-900/10"></div>

            <!-- Botones de acción -->
            <div class="mt-6 pb-2 flex items-center justify-between">
                <div></div>
                <div class="flex gap-4 items-center">
                    @if ($receipt->status === 'PAGADO')
                        <flux:button variant="danger"
                            href="{{ route('cancelarCFDI', $receipt->id) }}"
                            onclick="return confirm('¿Estás seguro de que deseas cancelar este recibo?')">
                            Cancelar recibo
                        </flux:button>
                    @endif

                    <a href="{{ url()->previous() }}">
                        <flux:button class="cursor-pointer">Regresar</flux:button>
                    </a>

                    @if ($receipt->status === 'PENDIENTE')
                        <flux:button variant="primary" href="{{ route('receipt.edit', $receipt->id) }}">
                            Editar
                        </flux:button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
