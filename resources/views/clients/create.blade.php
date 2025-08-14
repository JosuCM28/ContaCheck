<x-layouts.app :title="__('Crear Cliente')">
    <div class="container mx-auto p-10">
        <form  id="form" action="{{ route('client.store') }}"  method="post">
            @csrf
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Información del Cliente</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Ingresa los datos de un nuevo Cliente</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <!-- Nombre -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Nombre <span class="text-red-500">*</span></flux:label>
                                <flux:input name="name" id="name" type="text"
                                    oninput="this.value = this.value.toUpperCase();" autocomplete="given-name" required
                                    placeholder="Por favor escribe los nombres" />
                                <flux:error name="name" />
                            </flux:field>
                        </div>

                        <!-- Apellido -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Apellido <span class="text-red-500">*</span></flux:label>
                                <flux:input name="last_name" id="last_name" type="text"
                                    oninput="this.value = this.value.toUpperCase();" autocomplete="family-name" required
                                    placeholder="Por favor escribe los apellidos" />
                                <flux:error name="last_name" />
                            </flux:field>
                        </div>

                        <!-- Correo -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Correo <span class="text-red-500">*</span></flux:label>
                                <flux:input name="email" id="email" type="email" autocomplete="email" required
                                    placeholder="Por favor escribe el correo" />
                                <flux:error name="email" />
                            </flux:field>
                        </div>

                        <!-- Teléfono -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Teléfono</flux:label>
                                <flux:input name="phone" id="phone" type="number"
                                    oninput="this.value = this.value.slice(0, 10);"
                                    placeholder="Por favor escribe el número de teléfono" />
                                <flux:error name="phone" />
                            </flux:field>
                        </div>

                        <!-- RFC -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>RFC <span class="text-red-500">*</span></flux:label>
                                <flux:input name="rfc" id="rfc" type="text" maxlength="13"
                                    oninput="this.value = this.value.toUpperCase();" required
                                    placeholder="Por favor escribe el RFC" />
                                <flux:error name="rfc" />
                            </flux:field>
                        </div>

                        <!-- CURP -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>CURP</flux:label>
                                <flux:input name="curp" id="curp" type="text" maxlength="18"
                                    oninput="this.value = this.value.toUpperCase();"
                                    placeholder="Por favor escribe el CURP" />
                                <flux:error name="curp" />
                            </flux:field>
                        </div>


                        {{-- Dirección
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Dirección</flux:label>
                                <flux:input name="address" id="address" type="text"
                                    oninput="this.value = this.value.toUpperCase();"
                                    placeholder="Escribe la dirección" />
                                <flux:error name="address" />
                            </flux:field>
                        </div> --}}

                        <!-- Calle -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Calle <span class="text-red-500">*</span></flux:label>
                                <flux:input required name="street" id="street" type="text" class="w-full"
                                     oninput="this.value = this.value.toUpperCase();"
                                    value="{{ old('street') }}" placeholder="Escribe la calle" />
                                <flux:error name="street" />
                            </flux:field>
                        </div>

                        <!-- Colonia -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Colonia <span class="text-red-500">*</span></flux:label>
                                <flux:input required name="col" id="col" type="text" class="w-full"
                                 oninput="this.value = this.value.toUpperCase();"
                                    value="{{ old('col') }}" placeholder="Escribe la colonia" />
                                <flux:error name="col" />
                            </flux:field>
                        </div>

                        <!-- Número exterior -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Número Ext. <span class="text-red-500">*</span></flux:label>
                                <flux:input required name="num_ext" id="num_ext" type="text" class="w-full"
                                     oninput="this.value = this.value.toUpperCase();"
                                    value="{{ old('num_ext') }}" placeholder="Escribe el número exterior" />
                                <flux:error name="num_ext" />
                            </flux:field>
                        </div>

                        <!-- Ciudad / Municipio -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Municipio <span class="text-red-500">*</span></flux:label>
                                <flux:input required name="city" id="city" type="text" class="w-full"
                                    oninput="this.value = this.value.toUpperCase();"
                                    value="{{ old('city') }}" placeholder="Escribe el municipio" />
                                <flux:error name="city" />
                            </flux:field>
                        </div>

                        <!-- País -->
                        <input type="hidden" name="country" id="country" value="MÉXICO">

                        <!-- Localidad -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Localidad <span class="text-red-500">*</span></flux:label>
                                <flux:input required name="localities" id="localities" type="text" class="w-full"
                                     oninput="this.value = this.value.toUpperCase();"
                                    value="{{ old('localities') }}" placeholder="Escribe la localidad" />
                                <flux:error name="localities" />
                            </flux:field>
                        </div>
                        <!-- Código Postal -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>CP <span class="text-red-500">*</span></flux:label>
                                <flux:input name="cp" id="cp" type="number"
                                    oninput="this.value = this.value.slice(0, 5);" required
                                    placeholder="Por favor escribe el CP" />
                                <flux:error name="cp" />
                            </flux:field>
                        </div>

                        <!-- Estado -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Estado <span class="text-red-500">*</span></flux:label>
                                <flux:input name="state" id="state" type="text"
                                    oninput="this.value = this.value.toUpperCase();" required
                                    placeholder="Por favor escribe el estado" />
                                <flux:error name="state" />
                            </flux:field>
                        </div>

                        <!-- NSS -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>NSS</flux:label>
                                <flux:input name="nss" id="nss" type="number"
                                    oninput="this.value = this.value.slice(0, 11);"
                                    placeholder="Por favor escribe el NSS" />
                                <flux:error name="nss" />
                            </flux:field>
                        </div>

                        <!-- Contraseña SIEC -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Contraseña SIEC</flux:label>
                                <flux:input name="siec" id="siec" type="text"
                                    placeholder="Por favor escribe la contraseña SIEC" />
                                <flux:error name="siec" />
                            </flux:field>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Fecha de Nacimiento</flux:label>
                                <flux:input name="birthdate" id="birthdate" type="date"
                                    placeholder="Por favor digita la fecha de nacimiento" />
                                <flux:error name="birthdate" />
                            </flux:field>
                        </div>

                        <!-- Régimen -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Régimen <span class="text-red-500">*</span></flux:label>
                                <flux:select name="regime_id" id="regime_id" placeholder="Selecciona un régimen"
                                    required>
                                    @foreach ($regimes as $regime)
                                        <flux:select.option value="{{ $regime->id }}">{{ $regime->title }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="regime_id" />
                            </flux:field>
                        </div>

                        <!-- Estatus -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Estatus <span class="text-red-500">*</span></flux:label>
                                <flux:select name="status" id="status" placeholder="Selecciona el estatus"
                                    required>
                                    <flux:select.option value="active">Activo</flux:select.option>
                                    <flux:select.option value="inactive">Inactivo</flux:select.option>
                                </flux:select>
                                <flux:error name="status" />
                            </flux:field>
                        </div>

                        <!-- Usuario IDSE -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Usuario IDSE</flux:label>
                                <flux:input name="useridse" id="useridse" type="text"
                                    placeholder="Por favor escribe el usuario IDSE" />
                                <flux:error name="useridse" />
                            </flux:field>
                        </div>

                        <!-- Contraseña IDSE -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Contraseña IDSE</flux:label>
                                <flux:input name="idse" id="idse" type="text"
                                    placeholder="Por favor escribe la contraseña IDSE" />
                                <flux:error name="idse" />
                            </flux:field>
                        </div>

                        <!-- Usuario Sipare -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Usuario Sipare</flux:label>
                                <flux:input name="usersipare" id="usersipare" type="text"
                                    placeholder="Por favor escribe el usuario Sipare" />
                                <flux:error name="usersipare" />
                            </flux:field>
                        </div>

                        <!-- Contraseña Sipare -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Contraseña Sipare</flux:label>
                                <flux:input name="sipare" id="sipare" type="text"
                                    placeholder="Por favor escribe la contraseña Sipare" />
                                <flux:error name="sipare" />
                            </flux:field>
                        </div>

                        <!-- Contador Asignado -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Contador <span class="text-red-500">*</span></flux:label>
                                <flux:select name="counter_id" id="counter_id" placeholder="Selecciona un contador"
                                    required>
                                    @foreach ($counters as $counter)
                                        <flux:select.option value="{{ $counter->id }}">
                                            {{ $counter->name . ' ' . $counter->last_name }}</flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="counter_id" />
                            </flux:field>
                        </div>


                        {{-- Token --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Token <span class="text-red-500">*</span></flux:label>
                                <flux:input name="token" id="token" type="text"
                                    value="{{ $token }}" readonly required />
                                <flux:error name="token" />
                            </flux:field>
                        </div>

                        {{-- Nota --}}
                        <div class="sm:col-span-6">
                            <flux:field>
                                <flux:label>Nota</flux:label>
                                <flux:description>Escribe una nota para el cliente</flux:description>
                                <flux:textarea name="note" id="note" rows="5" maxlength="255"
                                    style="resize: none;" />
                                <flux:error name="note" />
                            </flux:field>
                        </div>

                    </div>
                </div>

                <div class="mt-6 pb-2 flex items-center justify-end gap-x-6">
                    <a href="#" onclick="history.back()">
                        <flux:button class="cursor-pointer">
                            Cancelar</flux:button>
                    </a>
                    <flux:button variant="primary" type="submit" class="cursor-pointer" id="saveButton">
                        <flux:icon.loading class="size-4 hidden" id="saveButtonIcon"/>
                        <span id="saveButtonText">Guardar</span>
                    </flux:button>
                </div>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('form').addEventListener('submit', function() {
            document.getElementById('saveButton').disabled = true;
            document.getElementById('saveButtonText').classList.add('hidden');
            document.getElementById('saveButtonIcon').classList.remove('hidden');
        });
    </script>
</x-layouts.app>
