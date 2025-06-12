<x-layouts.app :title="__('Actualizar Contador')" :subheading="__('Información personal')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="container mx-auto p-14">
                    <form action="{{ route('client.update', $client->id) }}" method="post">
                        @method('put')
                        @csrf
                        <div class="space-y-12">
                            <div class="border-b border-gray-900/10 pb-12">
                                <h2 class="text-base font-semibold leading-7 text-gray-900">Actualiza Información personal
                                </h2>
                                <p class="mt-1 text-sm leading-6 text-gray-600">Ingresa los datos del cliente que deseas
                                    actualizar</p>
                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <label for="name"
                                            class="block text-sm font-medium leading-6 text-gray-900">Nombre<span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <input type="text" name="name" id="name" autocomplete="given-name"
                                                oninput="this.value = this.value.toUpperCase();"
                                                value="{{ $client->name ?? '' }}"
                                                placeholder="{{ $client->name ? '' : 'No hay datos existentes' }}"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                            @error('name')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="last_name"
                                            class="block text-sm font-medium leading-6 text-gray-900">Apellido<span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <input type="text" name="last_name" id="last_name" autocomplete="family-name"
                                                oninput="this.value = this.value.toUpperCase();"
                                                value="{{ $client->last_name ?? '' }}"
                                                placeholder="{{ $client->last_name ? '' : 'No hay datos existentes' }}"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            @error('last_name')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="sm:col-span-3">
                                        <label for="email"
                                            class="block text-sm font-medium leading-6 text-gray-900">Correo</label>
                                        <div class="mt-2">
                                            <input id="email" name="email" type="email" autocomplete="email"
                                                value="{{ $client->email ?? '' }}"
                                                placeholder="{{ $client->email ? '' : 'No hay datos existentes' }}"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                            @error('email')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="token"
                                            class="block text-sm font-medium leading-6 text-gray-900">Token</label>
                                        <div class="mt-2">
                                            <input id="token" name="token" type="text" readonly value="{{ $client->token }}"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                            @error('token')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <fieldset class="col-span-3">
                                        <legend>FIEL</legend>
                                        <div class="sm:col-span-3">
                                            <label for="iniciofiel"
                                                class="block text-sm font-medium leading-6 text-gray-900">Fecha de
                                                Inicio</label>
                                            <div class="mt-2">
                                                <input name="iniciofiel" type="date" autocomplete="email"
                                                    value="{{ $client->credentials->iniciofiel ?? '' }}"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                                @error('email')
                                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label for="finfiel"
                                                class="block text-sm font-medium leading-6 text-gray-900">Fecha de
                                                vencimiento</label>
                                            <div class="mt-2">
                                                <input name="finfiel" type="date"
                                                    value="{{ $client->credentials->finfiel ?? '' }}"
                                                    placeholder="Cambia Contraseña (opcional)"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                @error('finfiel')
                                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </fieldset>


                                    <fieldset class="col-span-3 bg-gray-190">
                                        <legend>SELLO</legend>
                                        <div class="sm:col-span-3">
                                            <label for="iniciosello"
                                                class="block text-sm font-medium leading-6 text-gray-900">Fecha de
                                                Inicio</label>
                                            <div class="mt-2">
                                                <input id="email" name="iniciosello" type="date" autocomplete="email"
                                                    value="{{ $client->credentials->iniciosello ?? '' }}"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                                @error('iniciosello')
                                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label for="finsello"
                                                class="block text-sm font-medium leading-6 text-gray-900">Fecha de
                                                vencimiento</label>
                                            <div class="mt-2">
                                                <input id="password" name="finsello" type="date"
                                                    value="{{ $client->credentials->finsello ?? '' }}"
                                                    placeholder="Cambia Contraseña (opcional)"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                                @error('finsello')
                                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </fieldset>


                                    <div class="sm:col-span-2 sm:col-start-1">
                                        <label for="rfc"
                                            class="block text-sm font-medium leading-6 text-gray-900">RFC<span class="text-blue-500">*</span></label>
                                        <div class="mt-2">
                                            <input type="text" name="rfc" id="rfc"
                                                oninput="this.value = this.value.toUpperCase();"
                                                autocomplete="address-level2" value="{{ $client->rfc ?? '' }}"
                                                placeholder="{{ $client->rfc ? '' : 'No hay datos existentes' }}"
                                                maxlength="13"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                            @error('rfc')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="curp"
                                            class="block text-sm font-medium leading-6 text-gray-900">CURP</label>
                                        <div class="mt-2">
                                            <input type="text" name="curp" id="curp" autocomplete="curp"
                                                oninput="this.value = this.value.toUpperCase();" maxlength="18"
                                                value="{{ $client->curp ?? '' }}"
                                                placeholder="{{ $client->curp ? '' : 'No hay datos existentes' }}"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                            @error('curp')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="siec"
                                            class="block text-sm font-medium leading-6 text-gray-900">Contraseña
                                            SIEC</label>
                                        <div class="mt-2">
                                            <input name="siec" type="text"
                                                value="{{ $client->credentials->siec ?? '' }}"
                                                placeholder="{{ $client->credentials->siec ? '' : 'No hay datos existentes' }}"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            @error('siec')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="useridse"
                                            class="block text-sm font-medium leading-6 text-gray-900">Usuario IDSE</label>
                                        <div class="mt-2">
                                            <input type="text" name="useridse" 
                                                
                                                value="{{ $client->credentials->useridse ?? '' }}"
                                                placeholder="{{ $client->credentials->useridse ? '' : 'No hay datos existentes' }}"
                                                maxlength="18"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                            @error('useridse')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="idse"
                                            class="block text-sm font-medium leading-6 text-gray-900">Contraseña
                                            IDSE</label>
                                        <div class="mt-2">
                                            <input type="text" name="idse" value="{{ $client->credentials->idse ?? '' }}"
                                                placeholder="{{ $client->credentials->idse ? '' : 'No hay datos existentes' }}"
                                                maxlength="18"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                            @error('idse')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="regimen"
                                            class="block text-sm font-medium leading-6 text-gray-900">Regimen<span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <select name="regime_id"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                @foreach ($regimes as $regime)
                                                    <option value="{{ $regime->id }}"
                                                        {{ $client->regime_id == $regime->id ? 'selected' : '' }}>
                                                        {{ $regime->title }}
                                                    </option>
                                                @endforeach

                                            </select>

                                            @error('regimen')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="usersipare"
                                            class="block text-sm font-medium leading-6 text-gray-900">Usuario
                                            SIPARE</label>
                                        <div class="mt-2">
                                            <input type="text" name="usersipare" id="regime_id"
                                                autocomplete="regime_id"
                                                value="{{ $client->credentials->usersipare ?? '' }}"
                                                placeholder="{{ $client->credentials->usersipare ? '' : 'No hay datos existentes' }}"
                                                maxlength="18"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                            @error('usersipare')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="sipare"
                                            class="block text-sm font-medium leading-6 text-gray-900">Contraseña
                                            SIPARE</label>
                                        <div class="mt-2">
                                            <input type="text" name="sipare"
                                                value="{{ $client->credentials->sipare ?? '' }}"
                                                placeholder="{{ $client->credentials->sipare ? '' : 'No hay datos existentes' }}"
                                                maxlength="18"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                            @error('sipare')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="phone"
                                            class="block text-sm font-medium leading-6 text-gray-900">Telefono</label>
                                        <div class="mt-2">
                                            <input id="phone" name="phone" type="number"
                                                oninput="this.value = this.value.slice(0, 10);"
                                                value="{{ $client->phone ?? '' }}"
                                                placeholder="{{ $client->phone ? '' : 'No hay datos existentes' }}"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            @error('phone')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="sm:col-span-2 sm:col-start-1">
                                        <label for="address"
                                            class="block text-sm font-medium leading-6 text-gray-900">Direccion<span class="text-blue-500">*</span></label>
                                        <div class="mt-2">
                                            <input type="text" name="address" id="address"
                                                oninput="this.value = this.value.toUpperCase();"
                                                autocomplete="address-level2" value="{{ $client->address ?? '' }}"
                                                placeholder="{{ $client->address ? '' : 'No hay datos existentes' }}"
                                                maxlength="13"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                            @error('address')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="city"
                                            class="block text-sm font-medium leading-6 text-gray-900">Ciudad</label>
                                        <div class="mt-2">
                                            <input type="text" name="city" id="city" autocomplete="city"
                                                oninput="this.value = this.value.toUpperCase();" maxlength="18"
                                                value="{{ $client->city ?? '' }}"
                                                placeholder="{{ $client->city ? '' : 'No hay datos existentes' }}"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                            @error('city')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="cp"
                                            class="block text-sm font-medium leading-6 text-gray-900">CP<span class="text-blue-500">*</span></label>
                                        <div class="mt-2">
                                            <input name="cp" type="number"
                                                oninput="this.value = this.value.slice(0, 5);"
                                                value="{{ $client->cp ?? '' }}"
                                                placeholder="{{ $client->cp ? '' : 'No hay datos existentes' }}"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            @error('cp')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="state"
                                            class="block text-sm font-medium leading-6 text-gray-900">Estado</label>
                                        <div class="mt-2">
                                            <input id="state" name="state" type="text"
                                                oninput="this.value = this.value.toUpperCase();"
                                                value="{{ $client->state ?? '' }}"
                                                placeholder="{{ $client->state ? '' : 'No hay datos existentes' }}"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            @error('state')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="nss"
                                            class="block text-sm font-medium leading-6 text-gray-900">NSS</label>
                                        <div class="mt-2">
                                            <input type="text" name="nss" autocomplete="nss"
                                                value="{{ $client->nss ?? '' }}"
                                                placeholder="{{ $client->nss ? '' : 'No hay datos existentes' }}"
                                                maxlength="18"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                            @error('nss')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="birthdate"
                                            class="block text-sm font-medium leading-6 text-gray-900">Fecha de
                                            Nacimiento</label>
                                        <div class="mt-2">
                                            <input type="date" name="birthdate" id="birthdate" min="1900-1-1"
                                                value="{{ $client->birthdate ?? '' }}"
                                                placeholder="{{ $client->birthdate ? '' : 'No hay datos existentes' }}"
                                                autocomplete="postal-code"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                            @error('birthdate')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="counter"
                                            class="block text-sm font-medium leading-6 text-gray-900">Contador<span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <select name="counter_id"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                @foreach ($counters as $counter)
                                                    <option value="{{ $counter->id }}"
                                                        {{ $client->counter_id == $counter->id ? 'selected' : '' }}>
                                                        {{ $counter->full_name }}
                                                    </option>
                                                @endforeach

                                            </select>
                                            @error('counter')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="note"
                                            class="block text-sm font-medium leading-6 text-gray-900">Nota</label>
                                        <div class="mt-2">
                                            <input type="text" name="note" autocomplete="note"
                                                value="{{ $client->note ?? '' }}"
                                                placeholder="{{ $client->note ? '' : 'No hay datos existentes' }}"
                                                maxlength="255"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                            @error('note')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="status"
                                            class="block text-sm font-medium leading-6 text-gray-900">Estatus<span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <select name="status" id="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            <option value="active">Activo</option>
                                            <option value="inactive">Inactivo</option>
                                            </select>


                                            @error('status')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="auxone"
                                            class="block text-sm font-medium leading-6 text-gray-900">Extra 1</label>
                                        <div class="mt-2">
                                            <input id="state" name="auxone" type="text"
                                                value="{{ $client->credentials->auxone ?? '' }}"
                                                placeholder="{{ $client->credentials->auxone ? '' : 'No hay datos existentes' }}"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            @error('auxone')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="nss"
                                            class="block text-sm font-medium leading-6 text-gray-900">Extra 2</label>
                                        <div class="mt-2">
                                            <input type="text" name="auxtwo"
                                                value="{{ $client->credentials->auxtwo ?? '' }}"
                                                placeholder="{{ $client->credentials->auxtwo ? '' : 'No hay datos existentes' }}"
                                                maxlength="18"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                            @error('auxtwo')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="birthdate"
                                            class="block text-sm font-medium leading-6 text-gray-900">Extra 3</label>
                                        <div class="mt-2">
                                            <input type="text" name="auxthree"
                                                value="{{ $client->credentials->auxthree ?? '' }}"
                                                placeholder="{{ $client->credentials->auxthree ? '' : 'No hay datos existentes' }}"
                                                autocomplete="postal-code"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                            @error('auxthree')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <a href="{{ url()->previous() }}"
                                    class="text-sm font-semibold leading-6 text-gray-900">Cancelar</a>
                                <button type="submit"
                                    class="rounded-md bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    Actualizar
                                </button>
                            </div>

                    </form>
                </div>
            </div>
        </div>
        <script>
            const togglePassword = document.getElementById("togglePassword");
            const password = document.getElementById("password");
            togglePassword.addEventListener("click", (e) => {
                const type =
                    password.getAttribute("type") === "password" ? "text" : "password";
                password.setAttribute("type", type);
                e.target.classList.toggle("fa-eye");
            });
        </script>
</x-layouts.app>