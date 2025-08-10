<x-layouts.app :title="__('Actualizar Contador')" :subheading="__('Información personal')">
    <div class="container mx-auto p-10">
        <form id="form" action="{{ route('counter.update', $counter->id) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">
                        Actualiza Información personal
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        Ingresa los datos del contador que deseas actualizar
                    </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        {{-- Nombre --}}
                        <div class="sm:col-span-3">
                            <flux:field>
                                <flux:label>Nombre</flux:label>

                                <flux:input name="name" id="name" type="text" autocomplete="given-name"
                                    value="{{ $counter->name ?? '' }}"
                                    placeholder="{{ $counter->name ? '' : 'No hay datos existentes' }}" />
                                <flux:error name="name" />
                            </flux:field>
                        </div>

                        {{-- Apellido --}}
                        <div class="sm:col-span-3">
                            <flux:field>
                                <flux:label>Apellido</flux:label>

                                <flux:input name="last_name" id="last_name" type="text" autocomplete="family-name"
                                    value="{{ $counter->last_name ?? '' }}"
                                    placeholder="{{ $counter->last_name ? '' : 'No hay datos existentes' }}" />
                                <flux:error name="last_name" />
                            </flux:field>
                        </div>

                        {{-- Correo --}}
                        <div class="sm:col-span-3">
                            <flux:field>
                                <flux:label>Correo</flux:label>

                                <flux:input name="email" id="email" type="email" autocomplete="email"
                                    value="{{ $counter->user->email ?? '' }}"
                                    placeholder="{{ $counter->user->email ? '' : 'No hay datos existentes' }}" />
                                <flux:error name="email" />
                            </flux:field>
                        </div>

                        {{-- RFC --}}
                        <div class="sm:col-span-3">
                            <flux:field>
                                <flux:label>RFC</flux:label>

                                <flux:input name="rfc" id="rfc" type="text" autocomplete="address-level2"
                                    maxlength="13" value="{{ $counter->rfc ?? '' }}"
                                    placeholder="{{ $counter->rfc ? '' : 'No hay datos existentes' }}" />
                                <flux:error name="rfc" />
                            </flux:field>
                        </div>

                        {{-- CURP --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>CURP</flux:label>

                                <flux:input name="curp" id="curp" type="text" autocomplete="curp"
                                    maxlength="18" value="{{ $counter->curp ?? '' }}"
                                    placeholder="{{ $counter->curp ? '' : 'No hay datos existentes' }}" />
                                <flux:error name="curp" />
                            </flux:field>
                        </div>

                        {{-- Teléfono --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Teléfono</flux:label>

                                <flux:input name="phone" id="phone" type="text" maxlength="10"
                                    value="{{ $counter->phone ?? '' }}"
                                    placeholder="{{ $counter->phone ? '' : 'No hay datos existentes' }}" />
                                <flux:error name="phone" />
                            </flux:field>
                        </div>

                        {{-- Dirección --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Dirección</flux:label>

                                <flux:input name="address" id="address" type="text"
                                    autocomplete="address-level2" maxlength="150"
                                    value="{{ $counter->address ?? '' }}"
                                    placeholder="{{ $counter->address ? '' : 'No hay datos existentes' }}" />
                                <flux:error name="address" />
                            </flux:field>
                        </div>

                        {{-- Ciudad --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Ciudad</flux:label>

                                <flux:input name="city" id="city" type="text" autocomplete="city"
                                    maxlength="18" value="{{ $counter->city ?? '' }}"
                                    placeholder="{{ $counter->city ? '' : 'No hay datos existentes' }}" />
                                <flux:error name="city" />
                            </flux:field>
                        </div>

                        {{-- Código Postal --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>CP</flux:label>

                                <flux:input name="cp" id="cp" type="text" maxlength="5"
                                    value="{{ $counter->cp ?? '' }}"
                                    placeholder="{{ $counter->cp ? '' : 'No hay datos existentes' }}" />
                                <flux:error name="cp" />
                            </flux:field>
                        </div>

                        {{-- Estado --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Estado</flux:label>

                                <flux:input name="state" id="state" type="text"
                                    value="{{ $counter->state ?? '' }}"
                                    placeholder="{{ $counter->state ? '' : 'No hay datos existentes' }}" />
                                <flux:error name="state" />
                            </flux:field>
                        </div>

                        {{-- Régimen --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Régimen</flux:label>
                                <flux:select name="regime_id" id="regime_id" placeholder="Selecciona un régimen">
                                    @foreach ($regimes as $regime)
                                        @if ($counter->regime_id == $regime->id)
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

                        {{-- Fecha de nacimiento --}}
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label>Fecha de Nacimiento</flux:label>

                                <flux:input name="birthdate" id="birthdate" type="date" min="1900-01-01"
                                    value="{{ $counter->birthdate ?? '' }}"
                                    placeholder="{{ $counter->birthdate ? '' : 'No hay datos existentes' }}" />
                                <flux:error name="birthdate" />
                            </flux:field>
                        </div>

                    </div>
                </div>

                <div class="mt-6 pb-2 flex items-center justify-end gap-2">
                    <a href="{{ url()->previous() }}">
                        <flux:button class="cursor-pointer">Cancelar</flux:button>
                    </a>

                    <flux:button variant="primary" type="submit" class="cursor-pointer" id="saveButton">
                        <flux:icon.loading class="size-4 hidden" id="saveButtonIcon" />
                        <span id="saveButtonText">Actualizar</span>
                    </flux:button>


                </div>

            </div>
        </form>
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
    <script>
        //Boton de carga
        document.getElementById('form').addEventListener('submit', function() {
            document.getElementById('saveButton').disabled = true;
            document.getElementById('saveButtonText').classList.add('hidden');
            document.getElementById('saveButtonIcon').classList.remove('hidden');
        });
    </script>
</x-layouts.app>
