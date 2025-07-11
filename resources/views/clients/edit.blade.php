<x-layouts.app :title="__('Actualizar Cliente')" :subheading="__('Información personal')">
    <div class="container mx-auto p-14">
        <form action="{{ route('client.update', $client->id) }}" method="post">
            @method('put')
            @csrf
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">
                        Actualiza Información personal
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        Ingresa los datos del cliente que deseas actualizar
                    </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <!-- Nombre -->
                        <div class="sm:col-span-3">
                            <flux:field>
                                <flux:label>Nombre <span class="text-red-500">*</span></flux:label>
                                <flux:description>{{ $client->name ? '' : 'No hay datos existentes' }}
                                </flux:description>
                                <flux:input name="name" id="name" type="text" autocomplete="given-name"
                                    oninput="this.value = this.value.toUpperCase();"
                                    value="{{ old('name', $client->name) }}" placeholder="Escribe el nombre" />
                                <flux:error name="name" />
                            </flux:field>
                        </div>

                        <!-- Apellido -->
                        <div class="sm:col-span-3">
                            <flux:field>
                                <flux:label>Apellido <span class="text-red-500">*</span></flux:label>
                                <flux:description>{{ $client->last_name ? '' : 'No hay datos existentes' }}
                                </flux:description>
                                <flux:input name="last_name" id="last_name" type="text" autocomplete="family-name"
                                    oninput="this.value = this.value.toUpperCase();"
                                    value="{{ old('last_name', $client->last_name) }}"
                                    placeholder="Escribe el apellido" />
                                <flux:error name="last_name" />
                            </flux:field>
                        </div>

                        <!-- Correo -->
                        <div class="sm:col-span-3">
                            <flux:field>
                                <flux:label>Correo</flux:label>
                                <flux:description>{{ $client->email ? '' : 'No hay datos existentes' }}
                                </flux:description>
                                <flux:input name="email" id="email" type="email" autocomplete="email"
                                    value="{{ old('email', $client->email) }}" placeholder="Escribe el correo" />
                                <flux:error name="email" />
                            </flux:field>
                        </div>

                        <!-- Token (readonly) -->
                        <div class="sm:col-span-3">
                            <flux:field>
                                <flux:label>Token</flux:label>
                                <flux:input name="token" id="token" type="text" readonly
                                    value="{{ $client->token }}" />
                            </flux:field>
                        </div>

                        <!-- FIEL - Fecha de Inicio -->
<div class="sm:col-span-3">
    <flux:field>
        <flux:label>FIEL - Fecha de Inicio</flux:label>
        <flux:input
            name="iniciofiel"
            id="iniciofiel"
            type="date"
            class="w-full"
            value="{{ old('iniciofiel', optional($client->credentials)->iniciofiel) }}"
        />
        <flux:error name="iniciofiel" />
    </flux:field>
</div>

<!-- FIEL - Fecha de Vencimiento -->
<div class="sm:col-span-3">
    <flux:field>
        <flux:label>FIEL - Fecha de Vencimiento</flux:label>
        <flux:input
            name="finfiel"
            id="finfiel"
            type="date"
            class="w-full"
            value="{{ old('finfiel', optional($client->credentials)->finfiel) }}"
        />
        <flux:error name="finfiel" />
    </flux:field>
</div>

<!-- SELLO - Fecha de Inicio -->
<div class="sm:col-span-3">
    <flux:field>
        <flux:label>SELLO - Fecha de Inicio</flux:label>
        <flux:input
            name="iniciosello"
            id="iniciosello"
            type="date"
            class="w-full"
            value="{{ old('iniciosello', optional($client->credentials)->iniciosello) }}"
        />
        <flux:error name="iniciosello" />
    </flux:field>
</div>

<!-- SELLO - Fecha de Vencimiento -->
<div class="sm:col-span-3">
    <flux:field>
        <flux:label>SELLO - Fecha de Vencimiento</flux:label>
        <flux:input
            name="finsello"
            id="finsello"
            type="date"
            class="w-full"
            value="{{ old('finsello', optional($client->credentials)->finsello) }}"
        />
        <flux:error name="finsello" />
    </flux:field>
</div>

                        <!-- RFC -->
                        <div class="sm:col-span-2 sm:col-start-1">
                            <flux:field>
                                <flux:label>RFC <span class="text-red-500">*</span></flux:label>
                                <flux:input name="rfc" id="rfc" type="text" maxlength="13"
                                    autocomplete="address-level2" oninput="this.value = this.value.toUpperCase();"
                                    value="{{ old('rfc', $client->rfc) }}" placeholder="Escribe el RFC" />
                                <flux:error name="rfc" />
                            </flux:field>
                        </div>

                        <!-- CURP -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>CURP</flux:label>
                                <flux:input name="curp" id="curp" type="text" maxlength="18"
                                    autocomplete="curp" oninput="this.value = this.value.toUpperCase();"
                                    value="{{ old('curp', $client->curp) }}" placeholder="Escribe el CURP" />
                                <flux:error name="curp" />
                            </flux:field>
                        </div>

                        <!-- Contraseña SIEC -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Contraseña SIEC</flux:label>
                                <flux:input name="siec" id="siec" type="text"
                                    value="{{ old('siec', $client->credentials->siec) }}"
                                    placeholder="Escribe la contraseña SIEC" />
                                <flux:error name="siec" />
                            </flux:field>
                        </div>

                        <!-- Usuario IDSE -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Usuario IDSE</flux:label>
                                <flux:input name="useridse" id="useridse" type="text" maxlength="18"
                                    value="{{ old('useridse', $client->credentials->useridse) }}"
                                    placeholder="Escribe usuario IDSE" />
                                <flux:error name="useridse" />
                            </flux:field>
                        </div>

                        <!-- Contraseña IDSE -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Contraseña IDSE</flux:label>
                                <flux:input name="idse" id="idse" type="text" maxlength="18"
                                    value="{{ old('idse', $client->credentials->idse) }}"
                                    placeholder="Escribe contraseña IDSE" />
                                <flux:error name="idse" />
                            </flux:field>
                        </div>

                        <!-- Régimen -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Régimen <spanclass="text-red-500">*</span></flux:label>
                                <flux:select name="regime_id" id="regime_id" placeholder="Selecciona un régimen">
                                    @foreach ($regimes as $regime)
                                        @if (old('regime_id', $client->regime_id) == $regime->id)
                                            <flux:select.option value="{{ $regime->id }}" selected>
                                                {{ $regime->title }}</flux:select.option>
                                        @else
                                            <flux:select.option value="{{ $regime->id }}">{{ $regime->title }}
                                            </flux:select.option>
                                        @endif
                                    @endforeach
                                </flux:select>
                                <flux:error name="regime_id" />
                            </flux:field>
                        </div>

                        <!-- Usuario SIPARE -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Usuario SIPARE</flux:label>
                                <flux:input name="usersipare" id="usersipare" type="text" maxlength="18"
                                    value="{{ old('usersipare', $client->credentials->usersipare) }}"
                                    placeholder="Escribe usuario SIPARE" />
                                <flux:error name="usersipare" />
                            </flux:field>
                        </div>

                        <!-- Contraseña SIPARE -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Contraseña SIPARE</flux:label>
                                <flux:input name="sipare" id="sipare" type="text" maxlength="18"
                                    value="{{ old('sipare', $client->credentials->sipare) }}"
                                    placeholder="Escribe contraseña SIPARE" />
                                <flux:error name="sipare" />
                            </flux:field>
                        </div>

                        <!-- Teléfono -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Teléfono</flux:label>
                                <flux:input name="phone" id="phone" type="text" maxlength="10"
                                    oninput="this.value = this.value.slice(0, 10);"
                                    value="{{ old('phone', $client->phone) }}" placeholder="Escribe el teléfono" />
                                <flux:error name="phone" />
                            </flux:field>
                        </div>

                        <!-- Dirección -->
                        <div class="sm:col-span-2 sm:col-start-1">
                            <flux:field>
                                <flux:label>Dirección <span class="text-red-500">*</span></flux:label>
                                <flux:input name="address" id="address" type="text" class="w-full"
                                    maxlength="150" autocomplete="address-level2"
                                    oninput="this.value = this.value.toUpperCase();"
                                    value="{{ old('address', $client->address) }}"
                                    placeholder="{{ $client->address ? 'Escribe la dirección' : 'No hay datos existentes' }}" />
                                <flux:error name="address" />
                            </flux:field>
                        </div>

                        <!-- Ciudad -->
                        <div class="sm:col-span-2">

                            <flux:field>
                                <flux:label>Ciudad</flux:label>
                                <flux:input name="city" id="city" type="text" class="w-full"
                                    maxlength="18" oninput="this.value = this.value.toUpperCase();"
                                    value="{{ old('city', $client->city) }}"
                                    placeholder="{{ $client->city ? 'Escribe la ciudad' : 'No hay datos existentes' }}" />
                                <flux:error name="city" />
                            </flux:field>
                        </div>

                        <!-- CP -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>CP <span class="text-red-500">*</span></flux:label>
                                <flux:input name="cp" id="cp" type="text" class="w-full"
                                    maxlength="5" oninput="this.value = this.value.slice(0, 5);"
                                    value="{{ old('cp', $client->cp) }}"
                                    placeholder="{{ $client->cp ? 'Escribe el CP' : 'No hay datos existentes' }}" />
                                <flux:error name="cp" />
                            </flux:field>
                        </div>

                        <!-- Estado -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Estado</flux:label>
                                <flux:input name="state" id="state" type="text"
                                    oninput="this.value = this.value.toUpperCase();"
                                    value="{{ old('state', $client->state) }}" placeholder="Escribe el estado" />
                                <flux:error name="state" />
                            </flux:field>
                        </div>

                        <!-- NSS -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>NSS</flux:label>
                                <flux:input name="nss" id="nss" type="text" class="w-full"
                                    maxlength="18" autocomplete="nss" value="{{ old('nss', $client->nss) }}"
                                    placeholder="{{ $client->nss ? 'Escribe el NSS' : 'No hay datos existentes' }}" />
                                <flux:error name="nss" />
                            </flux:field>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Fecha de Nacimiento</flux:label>
                                <flux:input name="birthdate" id="birthdate" type="date" min="1900-01-01"
                                    value="{{ old('birthdate', $client->birthdate) }}" />
                                <flux:error name="birthdate" />
                            </flux:field>
                        </div>

                        <!-- Contador -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Contador <span class="text-red-500">*</span></flux:label>
                                <flux:select name="counter_id" id="counter_id" placeholder="Selecciona un contador">
                                    @foreach ($counters as $counter)
                                        @if (old('counter_id', $client->counter_id) == $counter->id)
                                            <flux:select.option value="{{ $counter->id }}" selected>
                                                {{ $counter->full_name }}</flux:select.option>
                                        @else
                                            <flux:select.option value="{{ $counter->id }}">{{ $counter->full_name }}
                                            </flux:select.option>
                                        @endif
                                    @endforeach
                                </flux:select>
                                <flux:error name="counter_id" />
                            </flux:field>
                        </div>

                        <!-- Nota -->
                        <!-- Nota -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Nota</flux:label>
                                <flux:input name="note" id="note" type="text" class="w-full"
                                    maxlength="255" value="{{ old('note', $client->note) }}"
                                    placeholder="{{ $client->note ? 'Escribe una nota' : 'No hay datos existentes' }}" />
                                <flux:error name="note" />
                            </flux:field>
                        </div>

                        <!-- Estatus -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Estatus <span class="text-red-500">*</span></flux:label>
                                <flux:select name="status" id="status" placeholder="Selecciona estatus">
                                    @foreach (['active' => 'Activo', 'inactive' => 'Inactivo'] as $value => $label)
                                        @if (old('status', $client->status) === $value)
                                            <flux:select.option value="{{ $value }}" selected>
                                                {{ $label }}</flux:select.option>
                                        @else
                                            <flux:select.option value="{{ $value }}">{{ $label }}
                                            </flux:select.option>
                                        @endif
                                    @endforeach
                                </flux:select>
                                <flux:error name="status" />
                            </flux:field>
                        </div>

                        <!-- Extra 1 -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Extra 1</flux:label>
                                <flux:input name="auxone" id="auxone" type="text"
                                    value="{{ old('auxone', $client->credentials->auxone) }}"
                                    placeholder="Campo Extra 1" />
                                <flux:error name="auxone" />
                            </flux:field>
                        </div>

                        <!-- Extra 2 -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Extra 2</flux:label>
                                <flux:input name="auxtwo" id="auxtwo" type="text"
                                    value="{{ old('auxtwo', $client->credentials->auxtwo) }}"
                                    placeholder="Campo Extra 2" />
                                <flux:error name="auxtwo" />
                            </flux:field>
                        </div>

                        <!-- Extra 3 -->
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Extra 3</flux:label>
                                <flux:input name="auxthree" id="auxthree" type="text"
                                    value="{{ old('auxthree', $client->credentials->auxthree) }}"
                                    placeholder="Campo Extra 3" />
                                <flux:error name="auxthree" />
                            </flux:field>
                        </div>

                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="{{ url()->previous() }}">
                        <flux:button class="cursor-pointer">Cancelar</flux:button>
                    </a>
                    <button type="submit">
                        <flux:button variant="primary" type="submit" class="cursor-pointer">Actualizar
                        </flux:button>
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
