<x-layouts.app :title="__('Actualizar cliente')" :subheading="__('Información personal')">
    <div class="container mx-auto p-12">
        <!-- Nombre y estado del cliente -->
        <div class="pb-10">
            <p class="text-center font-bold text-2xl">
                <i class="fa-solid fa-circle fa-xs mr-1"
                   title="{{ $client->status === 'active' ? 'Usuario Activo' : 'Usuario Inactivo' }}"
                   style="{{ $client->status === 'active' ? 'color: #1bc70f;' : 'color: #ef0b2d;' }}"></i>
                {{ $client->full_name }}
            </p>
            <div class="flex justify-center">
                <span class="label-text-alt">
                    @if ($client->counter)
                        Cliente de {{ $client->counter->full_name }}
                    @else
                        No tiene contador asociado
                    @endif
                </span>
            </div>
        </div>

        <!-- Mensaje de éxito -->
        @if (session('success'))
            <div
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 3000)"
                x-show="show"
                x-transition
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4"
                role="alert"
            >
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <!-- Encabezado para documentos -->
                <div class="flex gap-1">
                    <div class="items-center gap-x-3">
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Información personal</h2>
                    </div>
                </div>

                <!-- Modal para subir documento -->
                <flux:modal name="document-store" class="md:w-96">
                    <div class="space-y-6">
                        <div>
                            <flux:heading size="lg">Documentos</flux:heading>
                            <flux:text class="mt-2">Documentos asociados a este cliente</flux:text>
                        </div>
                        <form action="{{ route('file.store', $client->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="space-y-4">
                                <flux:input label="Documento" placeholder="Nombre del documento" type="text" name="title" oninput="this.value = this.value.toUpperCase();" required />
                                @error('title')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror

                                <flux:input type="file" label="Archivo" accept="application/pdf" name="file_path" required />
                                @error('file_path')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="flex mt-4">
                                <flux:spacer />
                                <flux:button type="submit" variant="primary" class="cursor-pointer">Subir documento</flux:button>
                            </div>
                        </form>
                    </div>
                </flux:modal>


                <!-- Cuadrícula de información -->
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Correo</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $client->email ? '' : 'text-gray-400 italic' }}">
                                {{ $client->email ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="birthdate" class="block text-sm font-medium leading-6 text-gray-900">Fecha de nacimiento</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $client->birthdate ? '' : 'text-gray-400 italic' }}">
                                {{ $client->birthdate ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="rfc" class="block text-sm font-medium leading-6 text-gray-900">RFC</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $client->rfc ? '' : 'text-gray-400 italic' }}">
                                {{ $client->rfc ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="curp" class="block text-sm font-medium leading-6 text-gray-900">CURP</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $client->curp ? '' : 'text-gray-400 italic' }}">
                                {{ $client->curp ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Teléfono</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $client->phone ? '' : 'text-gray-400 italic' }}">
                                {{ $client->phone ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="address" class="block text-sm font-medium leading-6 text-gray-900">Dirección</label>
                        <div class="mt-2 input input-filled peer" style="max-height: 4rem; overflow-y: auto;">
                            <p class="{{ $client->address ? '' : 'text-gray-400 italic' }}">
                                {{ $client->address ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="city" class="block text-sm font-medium leading-6 text-gray-900">Ciudad</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $client->city ? '' : 'text-gray-400 italic' }}">
                                {{ $client->city ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="cp" class="block text-sm font-medium leading-6 text-gray-900">CP</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $client->cp ? '' : 'text-gray-400 italic' }}">
                                {{ $client->cp ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="state" class="block text-sm font-medium leading-6 text-gray-900">Estado</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $client->state ? '' : 'text-gray-400 italic' }}">
                                {{ $client->state ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="regime" class="block text-sm font-medium leading-6 text-gray-900">Régimen</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $client->regime_id ? '' : 'text-gray-400 italic' }}">
                                {{ $client->regime->title ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="nss" class="block text-sm font-medium leading-6 text-gray-900">NSS</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $client->nss ? '' : 'text-gray-400 italic' }}">
                                {{ $client->nss ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="siec" class="block text-sm font-medium leading-6 text-gray-900">Contraseña SIEC</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $credential->siec ? '' : 'text-gray-400 italic' }}">
                                {{ $credential->siec ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="useridse" class="block text-sm font-medium leading-6 text-gray-900">Usuario IDSE</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $credential->useridse ? '' : 'text-gray-400 italic' }}">
                                {{ $credential->useridse ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="idse" class="block text-sm font-medium leading-6 text-gray-900">Contraseña IDSE</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $credential->idse ? '' : 'text-gray-400 italic' }}">
                                {{ $credential->idse ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="usersipare" class="block text-sm font-medium leading-6 text-gray-900">Usuario SIPARE</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $credential->usersipare ? '' : 'text-gray-400 italic' }}">
                                {{ $credential->usersipare ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="sipare" class="block text-sm font-medium leading-6 text-gray-900">Contraseña SIPARE</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $credential->sipare ? '' : 'text-gray-400 italic' }}">
                                {{ $credential->sipare ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="note" class="block text-sm font-medium leading-6 text-gray-900">Nota</label>
                        <div class="mt-2 input input-filled peer" style="max-height: 4rem; overflow-y: auto;">
                            <p class="{{ $client->note ? '' : 'text-gray-400 italic' }}">
                                {{ $client->note ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="token" class="block text-sm font-medium leading-6 text-gray-900">Token</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $client->token ? '' : 'text-gray-400 italic' }}">
                                {{ $client->token ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="iniciofiel" class="block text-sm font-medium leading-6 text-gray-900">Fecha de inicio (FIEL)</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $client->credentials->iniciofiel ? '' : 'text-gray-400 italic' }}">
                                {{ $client->credentials->iniciofiel ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="finfiel" class="block text-sm font-medium leading-6 text-gray-900">Fecha de vencimiento (FIEL)</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $client->credentials->finfiel ? '' : 'text-gray-400 italic' }}">
                                {{ $client->credentials->finfiel ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="iniciosello" class="block text-sm font-medium leading-6 text-gray-900">Fecha de inicio (SELLO)</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $client->credentials->iniciosello ? '' : 'text-gray-400 italic' }}">
                                {{ $client->credentials->iniciosello ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="finsello" class="block text-sm font-medium leading-6 text-gray-900">Fecha de vencimiento (SELLO)</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $client->credentials->finsello ? '' : 'text-gray-400 italic' }}">
                                {{ $client->credentials->finsello ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="auxone" class="block text-sm font-medium leading-6 text-gray-900">Extra 1</label>
                        <div class="mt-2 input input-filled peer" style="max-height: 4rem; overflow-y: auto;">
                            <p class="{{ $client->credentials->auxone ? '' : 'text-gray-400 italic' }}">
                                {{ $client->credentials->auxone ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="auxtwo" class="block text-sm font-medium leading-6 text-gray-900">Extra 2</label>
                        <div class="mt-2 input input-filled peer">
                            <p class="{{ $client->credentials->auxtwo ? '' : 'text-gray-400 italic' }}">
                                {{ $client->credentials->auxtwo ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3 form-control w-full sm:w-96">
                        <label for="auxthree" class="block text-sm font-medium leading-6 text-gray-900">Extra 3</label>
                        <div class="mt-2 input input-filled peer" style="max-height: 4rem; overflow-y: auto;">
                            <p class="{{ $client->credentials->auxthree ? '' : 'text-gray-400 italic' }}">
                                {{ $client->credentials->auxthree ?? 'Sin datos existentes' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="mt-6 pb-2 flex items-center justify-between">
                <div class="flex gap-4 items-center">
                    <flux:button id="showReceipts" class="cursor-pointer">Recibos</flux:button>
                    <flux:button id="showDocuments" class="cursor-pointer">Documentos</flux:button>

                </div>
                <div class="flex gap-4 items-center">
                    <a href="{{ url()->previous() }}">
                        <flux:button class="cursor-pointer">Cancelar</flux:button>
                    </a>
                    <flux:button variant="primary" href="{{ route('client.edit', $client->id) }}">Editar</flux:button>
                </div>
            </div>

            <livewire:document-store />

            <!-- Tabla de recibos -->
            <div class="hidden" id="tableClients">
                <livewire:receipt-table :client="$client->id" />
            </div>

            <!-- Tabla de documentos -->
            <div class="hidden" id="tableDocuments">
                <flux:modal.trigger name="document-store">
                    <flux:button icon="plus" class="mb-4 cursor-pointer">Agregar Documento</flux:buttom>
                </flux:modal.trigger>
                <livewire:document-table :client="$client" />
            </div>
        </div>
    </div>

    <script>
        document.getElementById('showReceipts').addEventListener('click', function() {
            const receipts = document.getElementById('tableClients');
            const showButton = document.getElementById('showReceipts');
            if (receipts.classList.contains('hidden')) {
                receipts.classList.remove('hidden');
                showButton.textContent = "Ocultar Recibos";
            } else {
                receipts.classList.add('hidden');
                showButton.textContent = "Recibos";
            }
        });

        document.getElementById('showDocuments').addEventListener('click', function() {
            const receipts = document.getElementById('tableDocuments');
            const showButton = document.getElementById('showDocuments');
            if (receipts.classList.contains('hidden')) {
                receipts.classList.remove('hidden');
                showButton.textContent = "Ocultar documentos";
            } else {
                receipts.classList.add('hidden');
                showButton.textContent = "Documentos";
            }
        });
    </script>
</x-layouts.app>