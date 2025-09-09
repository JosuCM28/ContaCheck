<x-layouts.app :title="__('Actualizar Recibo')" :subheading="__('Modifica los datos del recibo')">
    <div class="container mx-auto p-10">
        <form action="{{ route('receipt.update', $receipt->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Mensaje de éxito -->
            {{-- @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 my-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif --}}
            @if ($receipt->status == 'PENDIENTE')
                <div class="grid grid-cols-4 gap-6">

                    {{-- Tipo de Recibo --}}
                    <div class="col-span-4 md:col-span-2 py-2">
                        <flux:field>
                            <flux:label>Tipo de Recibo</flux:label>
                            <flux:select name="category_id" class="w-full">
                                @foreach ($categories as $category)
                                    <flux:select.option value="{{ $category->id }}"
                                        :selected="old('category_id', $receipt->category_id) == $category->id">
                                        {{ $category->name }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="category_id" />
                        </flux:field>
                    </div>

                    {{-- Realizado por --}}
                    <div class="col-span-4 md:col-span-2 py-2">
                        <flux:field>
                            <flux:label>Realizado por</flux:label>
                            <flux:select name="counter_id" class="w-full">
                                @foreach ($counters as $counter)
                                    <flux:select.option value="{{ $counter->id }}"
                                        :selected="old('counter_id', $receipt->counter_id) == $counter->id">
                                        {{ $counter->full_name }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="counter_id" />
                        </flux:field>
                    </div>

                    {{-- Contribuyente --}}
                    <div class="col-span-4 md:col-span-2 py-2">
                        <flux:field>
                            <flux:label>Contribuyente</flux:label>
                            <flux:select name="client_id" class="w-full">
                                @foreach ($clients as $client)
                                    <flux:select.option value="{{ $client->id }}"
                                        :selected="old('client_id', $receipt->client_id) == $client->id">
                                        {{ $client->full_name }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="client_id" />
                        </flux:field>
                    </div>

                    {{-- Método de Pago --}}
                    <div class="col-span-4 md:col-span-2 lg:col-span-1 py-2">
                        <flux:field>
                            <flux:label>Método de pago</flux:label>
                            <flux:select name="pay_method" id="pay_method" class="w-full">
                                <flux:select.option value="" disabled hidden>Selecciona un método de pago
                                </flux:select.option>
                                <flux:select.option value="EFECTIVO"
                                    :selected="old('pay_method', $receipt->pay_method) == 'EFECTIVO'">Efectivo
                                </flux:select.option>
                                <flux:select.option value="CHEQUE"
                                    :selected="old('pay_method', $receipt->pay_method) == 'CHEQUE'">Cheque
                                </flux:select.option>
                                <flux:select.option value="TRANSFERENCIA"
                                    :selected="old('pay_method', $receipt->pay_method) == 'TRANSFERENCIA'">Transferencia
                                </flux:select.option>
                            </flux:select>
                            <flux:error name="pay_method" />
                        </flux:field>
                    </div>

                    {{-- Monto --}}
                    <div class="col-span-4 md:col-span-2 lg:col-span-1 py-2">
                        <flux:field>
                            <flux:label>Monto ($MXN)</flux:label>
                            <flux:input name="mount" type="number" step="0.01"
                                value="{{ old('mount', $receipt->mount) }}" placeholder="Escribe el monto"
                                class="w-full" />
                            <flux:error name="mount" />
                        </flux:field>
                    </div>

                    {{-- Descripción --}}
                    <div class="col-span-4 md:col-span-2 py-2">
                        <flux:field>
                            <flux:label>Descripción</flux:label>
                            <flux:input name="description" type="text"
                                value="{{ old('description', $receipt->description ?? ($receipt->category->description ?? '')) }}"
                                oninput="this.value = this.value.toUpperCase();" placeholder="Escribe la descripción"
                                class="w-full" />
                            <flux:error name="description" />
                        </flux:field>
                    </div>

                    {{-- Fecha de Pago --}}
                    <div class="col-span-4 md:col-span-2 py-2">
                        <flux:field>
                            <flux:label>Fecha de pago</flux:label>
                            <flux:input name="payment_date" type="date"
                                value="{{ old('payment_date', $receipt->payment_date) }}" class="w-full" />
                            <flux:error name="payment_date" />
                        </flux:field>
                    </div>

                    {{-- Estado del recibo --}}

                    <div class="col-span-4 md:col-span-2 py-2">
                        <flux:field>
                            <flux:label>Estado</flux:label>
                            <flux:select name="status" class="w-full">
                                <flux:select.option value="PAGADO"
                                    :selected="old('status', $receipt->status) == 'PAGADO'">Pagado</flux:select.option>
                                <flux:select.option value="PENDIENTE"
                                    :selected="old('status', $receipt->status) == 'PENDIENTE'">Pendiente
                                </flux:select.option>
                                <flux:select.option value="CANCELADO"
                                    :selected="old('status', $receipt->status) == 'CANCELADO'">Cancelado
                                </flux:select.option>
                            </flux:select>
                            <flux:error name="status" />
                        </flux:field>
                    </div>

                    <div class="flex gap-4 col-span-4 md:col-span-2 py-2 ">
                        <div class="flex-1">
                            <flux:field>
                                <flux:label>Regimen</flux:label>
                                <flux:select name="regime_id" class="w-full">
                                    @foreach ($regimes as $regime)
                                        <flux:select.option value="{{ $regime->id }}"
                                            :selected="old('regime_id', $receipt->regime_id) == $regime->id">
                                            {{ $regime->title }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="regime_id" />
                            </flux:field>
                        </div>

                        <div class="flex-1">
                            <flux:field>
                                <flux:label>UsoCFDI</flux:label>
                                <flux:select name="usocfdi_id" class="w-full">
                                    @foreach ($usoscfdi as $usocfdi)
                                        <flux:select.option value="{{ $usocfdi->id }}"
                                            :selected="old('usocfdi_id', $receipt->usocfdi_id) == $usocfdi->id">
                                            {{ $usocfdi->title }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="usocfdi_id" />
                            </flux:field>
                        </div>
                    </div>




                    {{-- Separador antes de botones --}}
                    <div class="col-span-4 border-t pt-6 mt-6"></div>

                    {{-- Botones --}}
                    <div class="col-span-4 flex justify-end gap-4 mt-4">
                        <a href="{{ url()->previous() }}">
                            <flux:button class="cursor-pointer">Cancelar</flux:button>
                        </a>

                        <button type="submit" @if ($receipt->status != 'PENDIENTE') disabled @endif>
                            <flux:button variant="primary" class="cursor-pointer" type="submit">Actualizar
                            </flux:button>
                        </button>
                    </div>
                @else
                    <div class="col-span-4 md:col-span-2 py-2">
                        <flux:field>
                            <flux:label>El recibo no puede ser editado porque su estado es '{{ $receipt->status }}'
                            </flux:label>
                        </flux:field>
                    </div>

            @endif
    </div>
    </form>
    </div>
</x-layouts.app>
