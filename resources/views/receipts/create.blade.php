<x-layouts.app :title="__('Crear Recibo')">
    <div class="container mx-auto p-10">
        <form id="form" action="{{ route('receipt.store') }}" method="post">
            @csrf
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Información del Recibo</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Ingresa los datos para crear un nuevo recibo</p>

                    @if (session('success'))
                        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                            class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        {{-- Tipo de Recibo --}}
                        <div class="sm:col-span-3">
                            <flux:field>
                                <flux:label>Tipo de Recibo <span class="text-red-500">*</span></flux:label>
                                <flux:description>Por favor seleccione el tipo de recibo</flux:description>
                                <flux:select name="category_id" id="category_id" placeholder="Selecciona una categoría"
                                    required>
                                    @foreach ($categories as $category)
                                        <flux:select.option value="{{ $category->id }}">{{ $category->name }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="category_id" />
                            </flux:field>
                        </div>

                        {{-- Realizado por --}}
                        <div class="sm:col-span-3">
                            <flux:field>
                                <flux:label>Realizado por <span class="text-red-500">*</span></flux:label>
                                <flux:description>Seleccione el contador que realiza el recibo</flux:description>
                                <flux:select name="counter_id" id="counter_id" placeholder="Selecciona un contador"
                                    required>
                                    @foreach ($counters as $counter)
                                        <flux:select.option value="{{ $counter->id }}">{{ $counter->full_name }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="counter_id" />
                            </flux:field>
                        </div>

                        {{-- Contribuyente --}}
                        <div class="sm:col-span-3">
                            <flux:field>
                                <flux:label>Contribuyente <span class="text-red-500">*</span></flux:label>
                                <flux:description>Selecciona el contribuyente beneficiario</flux:description>
                                <flux:select name="client_id" id="client_id" placeholder="Selecciona un cliente"
                                    required>
                                    @foreach ($clients as $client)
                                        <flux:select.option value="{{ $client->id }}">{{ $client->full_name }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="client_id" />
                            </flux:field>
                        </div>

                        {{-- Método de Pago --}}
                        <div class="sm:col-span-3">
                            <flux:field>
                                <flux:label>Método de Pago <span class="text-red-500">*</span></flux:label>
                                <flux:description>Por favor seleccione el método de pago</flux:description>
                                <flux:select name="pay_method" id="pay_method"
                                    placeholder="Selecciona un método de pago" required>
                                    <flux:select.option value="EFECTIVO">Efectivo</flux:select.option>
                                    <flux:select.option value="CHEQUE">Cheque</flux:select.option>
                                    <flux:select.option value="TRANSFERENCIA">Transferencia Bancaria
                                    </flux:select.option>
                                </flux:select>
                                <flux:error name="pay_method" />
                            </flux:field>
                        </div>

                        {{-- Monto --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Monto $MXN <span class="text-red-500">*</span></flux:label>
                                <flux:description>Escribe el monto en número</flux:description>
                                <flux:input name="mount" id="mount" type="number" step="0.01"
                                    placeholder="Escribe el monto" icon="currency-dollar"
                                    oninput="this.value = this.value.toUpperCase();" required />
                                <flux:error name="mount" />
                            </flux:field>
                        </div>

                        {{-- Fecha del Recibo --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Fecha del Recibo <span class="text-red-500">*</span></flux:label>
                                <flux:description>Escribe la fecha del pago (MES DE [mes] DEL [año])</flux:description>
                                <flux:input name="concept" id="concept" type="text"
                                    oninput="this.value = this.value.toUpperCase();" placeholder="MES DE ABRIL DEL 2025"
                                    required />
                                <flux:error name="concept" />
                            </flux:field>
                        </div>

                        {{-- Fecha de Pago --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Fecha de Pago <span class="text-red-500">*</span></flux:label>
                                <flux:description>Por favor introduce la fecha de pago</flux:description>
                                <flux:input name="payment_date" id="payment_date" type="date" required />
                                <flux:error name="payment_date" />
                            </flux:field>
                        </div>

                        {{-- Estado --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Estado <span class="text-red-500">*</span></flux:label>
                                <flux:description>Por favor introduce el estado del recibo</flux:description>
                                <flux:select name="status" id="status" placeholder="Selecciona un estado" required>
                                    <flux:select.option value="PAGADO">Pagado</flux:select.option>
                                    <flux:select.option value="PENDIENTE">Pendiente</flux:select.option>
                                </flux:select>
                                <flux:error name="status" />
                            </flux:field>
                        </div>

                        {{-- Identificador --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Identificador <span class="text-red-500">*</span></flux:label>
                                <flux:description>El identificador sirve para llevar el control de los recibos
                                </flux:description>
                                <flux:input name="identificator" id="identificator" type="text"
                                    value="{{ $identificator }}" readonly />
                                <flux:error name="identificator" />
                            </flux:field>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="#" onclick="history.back()">
                        <flux:button class="cursor-pointer">Cancelar</flux:button>
                    </a>
                    {{-- <flux:button variant="primary" type="button" data-overlay="#modal-confirm">Guardar</flux:button> --}}
                    <flux:modal.trigger name="confirm-modal">
                        <flux:button variant="primary" type="button" class="cursor-pointer">Guardar</flux:button>
                    </flux:modal.trigger>
                </div>
            </div>

            <flux:modal name="confirm-modal" class="min-w-[22rem]">
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">¿Quieres enviar el recibo por correo?</flux:heading>
                        <flux:text class="mt-2">
                            <p>Si confirmas, el recibo será enviado por correo a la dirección de correo del cliente.</p>
                        </flux:text>
                    </div>
                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:modal.close>
                            <flux:button type="submit" variant="ghost" class="cursor-pointer">Solo guardar
                            </flux:button>
                        </flux:modal.close>
                        <flux:modal.close>
                            <flux:button type="submit" name="action" value="send" variant="primary"
                                class="cursor-pointer">Enviar por correo</flux:button>
                        </flux:modal.close>
                    </div>
                </div>
            </flux:modal>
        </form>
    </div>
</x-layouts.app>
