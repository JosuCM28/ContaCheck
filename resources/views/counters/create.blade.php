<x-layouts.app :title="__('Crear Contador')">
    <div class="container mx-auto p-10">
        <form action="{{ route('counter.store') }}" method="post">
            @csrf
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Información personal</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Ingresa los datos de un nuevo Contador</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        {{-- Nombre --}}
                        <div class="sm:col-span-3">
                            <flux:field>
                                <flux:label>Nombre <span class="text-red-500">*</span></flux:label>
                                <flux:description>Por favor escribe los nombres</flux:description>
                                <flux:input name="name" id="name" type="text"
                                    oninput="this.value = this.value.toUpperCase();" autocomplete="given-name" required/>
                                <flux:error name="name" />
                            </flux:field>
                        </div>

                        {{-- Apellido --}}
                        <div class="sm:col-span-3">
                            <flux:field>
                                <flux:label>Apellido <span class="text-red-500">*</span></flux:label>
                                <flux:description>Por favor escribe los apellidos</flux:description>
                                <flux:input name="last_name" id="last_name" type="text"
                                    oninput="this.value = this.value.toUpperCase();" autocomplete="family-name" required/>
                                <flux:error name="last_name" />
                            </flux:field>
                        </div>

                        {{-- Correo --}}
                        <div class="sm:col-span-3">
                            <flux:field>
                                <flux:label>Correo <span class="text-red-500">*</span></flux:label>
                                <flux:description>Por favor escribe el correo</flux:description>
                                <flux:input name="email" id="email" type="email" autocomplete="email" required/>
                                <flux:error name="email" />
                            </flux:field>
                        </div>

                        {{-- Contraseña --}}
                        <div class="sm:col-span-3">
                            <flux:field>
                                <flux:label>Contraseña <span class="text-red-500">*</span></flux:label>
                                <flux:description>Verifica o edita la contraseña</flux:description>

                                <flux:input name="password" type="password" value="{{ $password }}" viewable 
                                oninput="this.value = this.value.toUpperCase();" required />
                                <flux:error name="password" />
                            </flux:field>
                        </div>

                        {{-- RFC --}}
                        <div class="sm:col-span-2 sm:col-start-1">
                            <flux:field>
                                <flux:label>RFC <span class="text-red-500">*</span></flux:label>
                                <flux:description>Por favor escribe el RFC</flux:description>
                                <flux:input name="rfc" id="rfc" type="text" maxlength="13"
                                    oninput="this.value = this.value.toUpperCase();" required/>
                                <flux:error name="rfc" />
                            </flux:field>
                        </div>

                        {{-- CURP --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>CURP</flux:label>
                                <flux:description>Por favor escribe el CURP</flux:description>
                                <flux:input name="curp" id="curp" type="text" maxlength="18"
                                    oninput="this.value = this.value.toUpperCase();" />
                                <flux:error name="curp" />
                            </flux:field>
                        </div>

                        {{-- Teléfono --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Teléfono</flux:label>
                                <flux:description>Por favor escribe el número de teléfono</flux:description>
                                <flux:input name="phone" id="phone" type="number"
                                    oninput="this.value = this.value.slice(0, 10);" />
                                <flux:error name="phone" />
                            </flux:field>
                        </div>

                        {{-- Dirección --}}
                        <div class="sm:col-span-2 sm:col-start-1">
                            <flux:field>
                                <flux:label>Dirección <span class="text-red-500">*</span></flux:label>
                                <flux:description>Por favor escribe la dirección</flux:description>
                                <flux:input name="address" id="address" type="text" maxlength="150"
                                    oninput="this.value = this.value.toUpperCase();" required/>
                                <flux:error name="address" />
                            </flux:field>
                        </div>

                        {{-- Ciudad --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Ciudad</flux:label>
                                <flux:description>Por favor escribe la ciudad</flux:description>
                                <flux:input name="city" id="city" type="text" maxlength="18"
                                    oninput="this.value = this.value.toUpperCase();" />
                                <flux:error name="city" />
                            </flux:field>
                        </div>

                        {{-- Código Postal --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>CP <span class="text-red-500">*</span></flux:label>
                                <flux:description>Por favor escribe el CP</flux:description>
                                <flux:input name="cp" id="cp" type="number"
                                    oninput="this.value = this.value.slice(0, 5);" required />
                                <flux:error name="cp" />
                            </flux:field>
                        </div>

                        {{-- Estado --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Estado</flux:label>
                                <flux:description>Por favor escribe el estado</flux:description>
                                <flux:input name="state" id="state" type="text"
                                    oninput="this.value = this.value.toUpperCase();" />
                                <flux:error name="state" />
                            </flux:field>
                        </div>

                        {{-- Régimen --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Régimen <span class="text-red-500">*</span></flux:label>
                                <flux:description>Selecciona un régimen</flux:description>
                                <flux:select name="regime_id" id="regime_id" placeholder="Selecciona un régimen" required>
                                    @foreach ($regimes as $regime)
                                        <flux:select.option value="{{ $regime->id }}">{{ $regime->title }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="regime_id" />
                            </flux:field>
                        </div>

                        {{-- Fecha de nacimiento --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Fecha de Nacimiento</flux:label>
                                <flux:description>Por favor digita la fecha de nacimiento</flux:description>
                                <flux:input name="birthdate" id="birthdate" type="date" min="1900-1-1" />
                                <flux:error name="birthdate" />
                            </flux:field>
                        </div>

                    </div>
                </div>

                <div class="mt-6 pb-2 flex items-center justify-end gap-x-6">
                    <a href="#" onclick="history.back()">
                        <flux:button class="cursor-pointer">Cancelar</flux:button>
                    </a>

                    <flux:button variant="primary" type="submit" class="cursor-pointer">Guardar</flux:button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
