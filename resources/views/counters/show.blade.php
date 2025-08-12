<x-layouts.app :title="__('Contador')" :subheading="__('Información personal')">
        <div class="container mx-auto p-12 max-w-7xl">

            <!-- Encabezado -->
            <div class="text-center space-y-1 mb-10">
                <p class="text-2xl font-bold">
                    {{ $counter->name . ' ' . $counter->last_name }}
                </p>
                <p class="text-sm text-gray-500">
                    {{ $counter->clients->count() }} clientes asociados
                </p>
            </div>

            <flux:separator variant="subtle" class="mb-4" />

            <!-- Mensaje de éxito -->
            {{-- @if (session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                    class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif --}}

            <div class="p-8">
                <h2 class="text-lg font-semibold text-gray-800">Detalles del contador</h2>

                    <div class="my-10 grid grid-cols-1 sm:grid-cols-6 lg:grid-cols-9 gap-6">
                        <div class="sm:col-span-3 form-control w-full">
                            <label for="email"
                                class="block text-sm font-medium leading-6 text-gray-900">Correo</label>
                            <div class="mt-2 input input-filled peer">
                                <p class="{{ $counter->user->email ? '' : 'text-gray-400 italic' }} text-sm">
                                    {{ $counter->user->email ?? 'Sin datos existentes' }} </p>
                            </div>
                        </div>

                        <div class="sm:col-span-3 form-control w-full">
                            <label for="rfc" class="block text-sm font-medium leading-6 text-gray-900">Fecha de
                                nacimiento</label>
                            <div class="mt-2 input input-filled peer">
                                <p class="{{ $counter->birthdate ? '' : 'text-gray-400 italic' }} text-sm">
                                    {{ \Carbon\Carbon::parse($counter->birthdate)->format('d/m/Y') ?? 'Sin datos existentes' }} </p>
                            </div>
                        </div>


                        <div class="sm:col-span-3 form-control w-full">
                            <label for="city"
                                class="block text-sm font-medium leading-6 text-gray-900">RFC</label>
                            <div class="mt-2 input input-filled peer">
                                <p class="{{ $counter->rfc ? '' : 'text-gray-400 italic' }} text-sm">
                                    {{ $counter->rfc ?? 'Sin datos existentes' }} </p>
                            </div>
                        </div>

                        <div class="sm:col-span-3 form-control w-full">
                            <label for="curp"
                                class="block text-sm font-medium leading-6 text-gray-900">CURP</label>
                            <div class="mt-2 input input-filled peer">
                                <p class="{{ $counter->curp ? '' : 'text-gray-400 italic' }} text-sm">
                                    {{ $counter->curp ?? 'Sin datos existentes' }} </p>
                            </div>
                        </div>

                        <div class="sm:col-span-3 form-control w-full">
                            <label for="curp"
                                class="block text-sm font-medium leading-6 text-gray-900">Telefono</label>
                            <div class="mt-2 input input-filled peer">
                                <p class="{{ $counter->phone ? '' : 'text-gray-400 italic' }} text-sm">
                                    {{ $counter->phone ?? 'Sin datos existentes' }} </p>
                            </div>
                        </div>

                        <div class="sm:col-span-3 form-control w-full">
                            <label for="curp"
                                class="block text-sm font-medium leading-6 text-gray-900">Drección</label>
                            <div class="mt-2 input input-filled peer" style="max-height: 4rem; overflow-y: auto;">
                                <p class="{{ $counter->address ? '' : 'text-gray-400 italic' }} text-sm">
                                    {{ $counter->address ?? 'Sin datos existentes' }} </p>
                            </div>
                        </div>

                        <div class="sm:col-span-3 form-control w-full">
                            <label for="curp"
                                class="block text-sm font-medium leading-6 text-gray-900">Ciudad</label>
                            <div class="mt-2 input input-filled peer">
                                <p class="{{ $counter->city ? '' : 'text-gray-400 italic' }} text-sm">
                                    {{ $counter->city ?? 'Sin datos existentes' }} </p>
                            </div>
                        </div>

                        <div class="sm:col-span-3 form-control w-full">
                            <label for="curp"
                                class="block text-sm font-medium leading-6 text-gray-900">CP</label>
                            <div class="mt-2 input input-filled peer">
                                <p class="{{ $counter->cp ? '' : 'text-gray-400 italic' }} text-sm">
                                    {{ $counter->cp ?? 'Sin datos existentes' }} </p>
                            </div>
                        </div>

                        <div class="sm:col-span-3 form-control w-full">
                            <label for="curp"
                                class="block text-sm font-medium leading-6 text-gray-900">Estado</label>
                            <div class="mt-2 input input-filled peer">
                                <p class="{{ $counter->state ? '' : 'text-gray-400 italic' }} text-sm">
                                    {{ $counter->state ?? 'Sin datos existentes' }} </p>
                            </div>
                        </div>

                        <div class="sm:col-span-3 form-control w-full">
                            <label for="curp"
                                class="block text-sm font-medium leading-6 text-gray-900">Regimen</label>
                            <div class="mt-2 input input-filled peer" style="max-height: 4rem; overflow-y: auto;">
                                <p class="{{ $counter->regime ? '' : 'text-gray-400 italic' }} text-sm">
                                    {{ $counter->regime->title ?? 'Sin datos existentes' }} </p>
                            </div>
                        </div>

                        <div class="sm:col-span-2 form-control w-full">
                            <label for="curp" class="block text-sm font-medium leading-6 text-gray-900">No.
                                de clientes</label>
                            <div class="mt-2 input input-filled peer text-sm">
                                <p> {{ $counter->clients->count() }} </p>
                            </div>
                        </div>
                    </div>

                <div class="border-b border-gray-900/10"></div>

                <div class="mt-6 pb-2 flex items-center justify-between">
                    <flux:button id="showClients" class="cursor-pointer" value="Ver Clientes">Ver Clientes</flux:button>
                    <div class="flex gap-4 items-center">
                        <a href="{{ url()->previous() }}">
                            <flux:button class="cursor-pointer">Cancelar</flux:button>
                        </a>
                        <flux:button variant="primary" href="{{ route('counter.edit', $counter->id) }}">Editar</flux:button>
                    </div>
                </div>
                
                <div class="hidden" id="tableClients">
                    <livewire:client-table :counter="$counter->id" />
                </div>

            </div>
        </div>

    <script>
        document.getElementById('showClients').addEventListener('click', function() {
            const clients = document.getElementById('tableClients');
            const verClientes = document.getElementById('showClients');
            if (clients.classList.contains('hidden')) {
                clients.classList.remove('hidden');
                verClientes.textContent = "Ocultar clientes";

            } else {
                clients.classList.add('hidden');
                verClientes.textContent = "Ver clientes";
            }
        });
    </script>
</x-layouts.app>