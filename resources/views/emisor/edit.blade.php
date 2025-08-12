<x-layouts.app :title="__('Datos Emisor')">
    <div class="container mx-auto p-14">
        <div class="pb-10">
            <form id="form" action="{{ route('emisor.update') }}" method="post">
                @method('put')
                @csrf

                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-base font-semibold leading-7 text-gray-900">
                            Actualiza Información del Emisor
                        </h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">
                            Ingresa los datos del emisor que deseas actualizar
                        </p>

                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label>Nombre</flux:label>
                                    <flux:input name="name" id="name" type="text" oninput="this.value = this.value.toUpperCase();"
                                        value="{{ old('name', $company->name) }}" placeholder="Escribe el nombre" />
                                    <flux:error name="name" />
                                </flux:field>
                            </div>

                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label>Apellido</flux:label>
                                    <flux:input name="last_name" id="last_name" type="text" oninput="this.value = this.value.toUpperCase();"
                                        value="{{ old('last_name', $company->last_name) }}"
                                        placeholder="Escribe el apellido" />
                                    <flux:error name="last_name" />
                                </flux:field>
                            </div>

                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label>CURP</flux:label>
                                    <flux:input name="curp" id="curp" type="text" oninput="this.value = this.value.toUpperCase();" maxlength="18"
                                        value="{{ old('curp', $company->curp) }}" placeholder="CURP" />
                                    <flux:error name="curp" />
                                </flux:field>
                            </div>

                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label>RFC</flux:label> 
                                    <flux:input name="rfc" id="rfc" type="text" oninput="this.value = this.value.toUpperCase();" maxlength="13"
                                        value="{{ old('rfc', $company->rfc) }}" placeholder="RFC" />
                                    <flux:error name="rfc" />
                                </flux:field>
                            </div>

                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label>Nombre comercial</flux:label>
                                    <flux:input name="nombre_comercial" id="nombre_comercial" type="text" oninput="this.value = this.value.toUpperCase();"
                                        value="{{ old('nombre_comercial', $company->nombre_comercial) }}"
                                        placeholder="Nombre comercial" />
                                    <flux:error name="nombre_comercial" />
                                </flux:field>
                            </div>

                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label>Correo</flux:label>
                                    <flux:input name="email" id="email" type="email" autocomplete="email"
                                        value="{{ old('email', $company->email) }}" placeholder="Correo electrónico" />
                                    <flux:error name="email" />
                                </flux:field>
                            </div>

                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label>Teléfono</flux:label>
                                    <flux:input name="phone" id="phone" type="phone" maxlength="10" oninput="this.value = this.value.slice(0, 10);"
                                        value="{{ old('phone', $company->phone) }}" placeholder="Teléfono principal" />
                                    <flux:error name="phone" />
                                </flux:field>
                            </div>

                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label>Teléfono secundario</flux:label>
                                    <flux:input name="phone2" id="phone2" type="text" maxlength="10" oninput="this.value = this.value.slice(0, 10);"
                                        value="{{ old('phone2', $company->phone2) }}"
                                        placeholder="Teléfono alternativo" />
                                    <flux:error name="phone2" />
                                </flux:field>
                            </div>

                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label>Calle</flux:label>
                                    <flux:input name="street" id="street" type="text" oninput="this.value = this.value.toUpperCase();"
                                        value="{{ old('street', $company->street) }}" placeholder="Calle" />
                                    <flux:error name="street" />
                                </flux:field>
                            </div>

                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label>Número exterior</flux:label>
                                    <flux:input name="num_ext" id="num_ext" type="text"
                                        value="{{ old('num_ext', $company->num_ext) }}"
                                        placeholder="Número exterior" />
                                    <flux:error name="num_ext" />
                                </flux:field>
                            </div>

                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label>Colonia</flux:label>
                                    <flux:input name="col" id="col" type="text" oninput="this.value = this.value.toUpperCase();"
                                        value="{{ old('col', $company->col) }}" placeholder="Colonia" />
                                    <flux:error name="col" />
                                </flux:field>
                            </div>

                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label>CP</flux:label>
                                    <flux:input name="cp" id="cp" type="text" maxlength="5" oninput="this.value = this.value.slice(0, 5);"
                                        value="{{ old('cp', $company->cp) }}" placeholder="Código Postal" />
                                    <flux:error name="cp" />
                                </flux:field>
                            </div>

                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label>Estado</flux:label>
                                    <flux:input name="state" id="state" type="text" oninput="this.value = this.value.toUpperCase();"
                                        value="{{ old('state', $company->state) }}" placeholder="Estado" />
                                    <flux:error name="state" />
                                </flux:field>
                            </div>

                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label>Ciudad</flux:label>
                                    <flux:input name="city" id="city" type="text" oninput="this.value = this.value.toUpperCase();"
                                        value="{{ old('city', $company->city) }}" placeholder="Ciudad" />
                                    <flux:error name="city" />
                                </flux:field>
                            </div>

                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label>Localidad</flux:label>
                                    <flux:input name="localities" id="localities" type="text" oninput="this.value = this.value.toUpperCase();"
                                        value="{{ old('localities', $company->localities) }}"
                                        placeholder="Localidad" />
                                    <flux:error name="localities" />
                                </flux:field>
                            </div>

                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label>Referencia</flux:label>
                                    <flux:input name="referer" id="referer" type="text" oninput="this.value = this.value.toUpperCase();"
                                        value="{{ old('referer', $company->referer) }}" placeholder="Referencia" />
                                    <flux:error name="referer" />
                                </flux:field>
                            </div>

                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label>Régimen fiscal</flux:label>
                                    <flux:select name="regime_id" id="regime_id" placeholder="Selecciona un régimen">
                                        @foreach ($regimes as $regime)
                                            @if (old('regime_id', $company->regime_id) == $regime->id)
                                                <flux:select.option value="{{ $regime->id }}" selected>
                                                    {{ $regime->title }}
                                                </flux:select.option>
                                            @else
                                                <flux:select.option value="{{ $regime->id }}">
                                                    {{ $regime->title }}
                                                </flux:select.option>
                                            @endif
                                        @endforeach
                                    </flux:select>
                                    <flux:error name="regime_id" />
                                </flux:field>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="{{ url()->previous() }}">
                        <flux:button class="cursor-pointer">Cancelar</flux:button>
                    </a>
                    <button type="submit">
                        <flux:button variant="primary" type="submit" class="cursor-pointer" id="saveButton">
                        <flux:icon.loading class="size-4 hidden" id="saveButtonIcon" />
                        <span id="saveButtonText"">Actualizar</span>
                        </flux:button>
                    </button>
                </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('form').addEventListener('submit', function() {
            document.getElementById('saveButton').disabled = true;
            document.getElementById('saveButtonText').classList.add('hidden');
            document.getElementById('saveButtonIcon').classList.remove('hidden');
        });
    </script>
</x-layouts.app>

