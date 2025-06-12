<x-layouts.app :title="__('Ver Contador')" :subheading="__('Información personal')">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mx-auto sm:px-6 lg:px-8">
                <div class="container mx-auto p-12">
                    <form action="{{ route('counter.destroy', $counter->id) }}" method="post"
                        onsubmit="return confirmDelete(event)">
                        @csrf
                        @method('DELETE')
                        <button type="submit"class="absolute top-4 right-4 text-gray-600 hover:text-red-600"
                            id="deleteButton">
                            <i class="fa-solid fa-xmark fa-lg"></i>
                        </button>
                    </form>
                    <div class="pb-10">
                        <p class="text-center font-bold text-2xl">{{ $counter->name . ' ' . $counter->last_name }} </p>
                    </div>

                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12 tex">

                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="email"
                                        class="block text-sm font-medium leading-6 text-gray-900">Correo</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $counter->user->email ? '' : 'text-gray-400 italic' }}">
                                            {{ $counter->user->email ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="rfc" class="block text-sm font-medium leading-6 text-gray-900">Fecha de
                                        nacimiento</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $counter->birthdate ? '' : 'text-gray-400 italic' }}">
                                            {{ $counter->birthdate ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>


                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="city"
                                        class="block text-sm font-medium leading-6 text-gray-900">RFC</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $counter->rfc ? '' : 'text-gray-400 italic' }}">
                                            {{ $counter->rfc ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="curp"
                                        class="block text-sm font-medium leading-6 text-gray-900">CURP</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $counter->curp ? '' : 'text-gray-400 italic' }}">
                                            {{ $counter->curp ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="curp"
                                        class="block text-sm font-medium leading-6 text-gray-900">Telefono</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $counter->phone ? '' : 'text-gray-400 italic' }}">
                                            {{ $counter->phone ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="curp"
                                        class="block text-sm font-medium leading-6 text-gray-900">Drección</label>
                                    <div class="mt-2 input input-filled peer" style="max-height: 4rem; overflow-y: auto;">
                                        <p class="{{ $counter->address ? '' : 'text-gray-400 italic' }}">
                                            {{ $counter->address ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="curp"
                                        class="block text-sm font-medium leading-6 text-gray-900">Ciudad</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $counter->city ? '' : 'text-gray-400 italic' }}">
                                            {{ $counter->city ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="curp"
                                        class="block text-sm font-medium leading-6 text-gray-900">CP</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $counter->cp ? '' : 'text-gray-400 italic' }}">
                                            {{ $counter->cp ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="curp"
                                        class="block text-sm font-medium leading-6 text-gray-900">Estado</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $counter->state ? '' : 'text-gray-400 italic' }}">
                                            {{ $counter->state ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="curp"
                                        class="block text-sm font-medium leading-6 text-gray-900">Regimen</label>
                                    <div class="mt-2 input input-filled peer" style="max-height: 4rem; overflow-y: auto;">
                                        <p class="{{ $counter->regime ? '' : 'text-gray-400 italic' }}">
                                            {{ $counter->regime->title ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>



                                <div class="sm:col-span-2 form-control w-full sm:w-96">
                                    <label for="curp" class="block text-sm font-medium leading-6 text-gray-900">No.
                                        de clientes</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p> {{ $counter->clients->count() }} </p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="mt-6 pb-2 flex items-center justify-end gap-x-6">
                                <a href="{{ url()->previous() }}">
                                    <flux:button class="cursor-pointer">Cancelar</flux:button>
                                </a>

                                <flux:button variant="primary" href="{{ route('counter.edit', $counter->id) }}">Editar</flux:button>
                            </div>

                       
                        <flux:button variant="filled" id="showClients" class="cursor-pointer">Ver Clientes</flux:button>
                        <div class="hidden" id="tableClients">
                            <livewire:client-table :counter="$counter->id" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('showClients').addEventListener('click', function() {
            const clients = document.getElementById('tableClients');
            const verClientes = document.getElementById('showClients');
            if (clients.classList.contains('hidden')) {
                clients.classList.remove('hidden');
                verClientes.value = "Ocultar clientes";

            } else {
                clients.classList.add('hidden');
                verClientes.value = "Ver clientes";
            }
        });

        function confirmDelete(event) {
            event.preventDefault(); // Evita que el formulario se envíe automáticamente

            if (confirm("¿Estás seguro de que deseas eliminar este contador?")) {
                event.target.submit(); 
        }}
    </script>

</x-layouts.app>