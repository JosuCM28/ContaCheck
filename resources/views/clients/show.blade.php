<x-layouts.app :title="__('Actualizar Cliente')" :subheading="__('Información personal')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg ">
                <div class="container mx-auto p-12">
                    <form action="{{ route('client.destroy', $client->id) }}" method="post"
                        onsubmit="return confirmDelete(event)">
                        @csrf
                        @method('DELETE')
                        <button type="submit"class="absolute top-4 right-4 text-gray-600 hover:text-red-600"
                            id="deleteButton">
                            <i class="fa-solid fa-xmark fa-lg"></i>
                        </button>
                    </form>
                    <div class="pb-11">
                        <p class="text-center font-bold text-2xl "><i class="fa-solid fa-circle  fa-xs mr-1"
                                title="{{ $client->status === 'active' ? 'Usuario Activo' : 'Usuario Inactivo' }}"
                                style="{{ $client->status === 'active' ? 'color: #1bc70f;' : 'color: #ef0b2d;' }} "></i>
                            {{ $client->full_name }} </p>


                        <div class="flex justify-center">
                        <flux:link href="{{route('counter.show',$client->counter->id)}}" variant="subtle"> @if ($client->counter)
                                    Cliente de {{ $client->counter->full_name }}
                                @else
                                    No tiene contador asociado
                                @endif</flux:link>

                        </div>

                    </div>

                    @if (@session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif




                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-8">

                            <div class="flex gap-1">
                                <div class="dropdown relative inline-flex rtl:[--placement:bottom-end] mt-0.5">
                                    <button id="dropdown-menu-icon " type="button"
                                        class="dropdown-toggle btn btn-square btn-text btn-xs" aria-haspopup="menu"
                                        aria-expanded="false" aria-label="Dropdown">
                                        <span class="icon-[solar--alt-arrow-down-linear] size-6"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-open:opacity-100 hidden min-w-10" role="menu"
                                        aria-orientation="vertical" aria-labelledby="dropdown-menu-icon">
                                        <li> <a type="button" class="dropdown-item" href="#" aria-haspopup="dialog"
                                                alt="Ver documentos" title="Ver documentos" aria-expanded="false"
                                                aria-controls="scroll-inside-modal"
                                                data-overlay="#scroll-inside-modal">Documentos </a></li>
                                    </ul>
                                </div>
                                <div>
                                    <!-- Modal 1 para ver documentos -->
                                    <div id="scroll-inside-modal" class="overlay modal overlay-open:opacity-100 hidden"
                                        role="dialog" tabindex="-1">
                                        <div class="modal-dialog overlay-open:opacity-100">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Documentos</h3>
                                                    <button type="button"
                                                        class="btn btn-text btn-circle btn-sm absolute end-3 top-3"
                                                        aria-label="Close" data-overlay="#scroll-inside-modal">
                                                        <span class="icon-[tabler--x] size-4"></span>
                                                    </button>
                                                </div>
                                                <div class="modal-body ">
                                                    @foreach ($client->document as $document)
                                                        <div class="flex justify-between items-center mb-3  ">
                                                            <div class="flex items-center gap-x-3 ">
                                                                <span class="icon-[fa6-solid--file-pdf]"
                                                                    style="width: 25px; height: 25px; color: #c91818;"></span>

                                                                <p>{{ $document->title }}</p>

                                                            </div>

                                                            <div class="flex items-center gap-x-2">
                                                                <a href="{{ route('file.download', $document->id) }}"
                                                                    target="_blank"
                                                                    class="btn btn-square   text-white  hover:border-[#f8fafc] hover:bg-opaciti-95[#1877F2]/90"
                                                                    aria-label="Facebook Icon Button">
                                                                    <span class="icon-[ic--round-download]"
                                                                        style="width: 25px; height: 25px; color: #3791f1;"></span>
                                                                </a>
                                                                <a href="{{ asset('storage/' . $document->file_path) }}"
                                                                    target="_blank"
                                                                    class="btn btn-square   text-white  hover:border-[#f8fafc] hover:bg-opaciti-95[#1877F2]/90"
                                                                    aria-label="Facebook Icon Button">
                                                                    <span class="icon-[weui--eyes-on-outlined]"
                                                                        style="width: 24px; height: 24px; color: #1cbe9d;"></span>
                                                                </a>
                                                                <form action="{{ route('file.destroy', $document->id) }}"
                                                                    method="post">
                                                                    @method('delete')
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="btn btn-square   text-white  hover:border-[#f8fafc] hover:bg-opaciti-95[#1877F2]/90"
                                                                        aria-label="Facebook Icon Button">
                                                                        <span class="icon-[tdesign--delete-1]"
                                                                            style="width: 25px; height: 25px; color: #d00610;"></span>
                                                                    </button>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-soft btn-secondary"
                                                        data-overlay="#scroll-inside-modal">Cerrar</button>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary"
                                                            aria-haspopup="dialog" aria-expanded="false"
                                                            aria-controls="toggle-bn-second-modal"
                                                            data-overlay="#toggle-bn-second-modal">
                                                            Subir PDF
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal 2 para subir documento  -->
                                    <div id="toggle-bn-second-modal" class="overlay modal overlay-open:opacity-100 hidden"
                                        role="dialog" tabindex="-1">
                                        <div class="modal-dialog overlay-open:opacity-100">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Subir </h3>
                                                    <button type="button"
                                                        class="btn btn-text btn-circle btn-sm absolute end-3 top-3"
                                                        aria-label="Close" data-overlay="#scroll-inside-modal"><span
                                                            class="icon-[tabler--x] size-4"></span></button>
                                                </div>
                                                <form action="{{ route('file.store', $client->id) }}" method="post"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body pt-0">
                                                        <div class="mb-0.5  gap-4 max-sm:flex-col">
                                                            <label class="form-control w-full mb-4">
                                                                <div class="label">
                                                                    <span class="label-text">Titulo</span>
                                                                </div>
                                                                <input type="text" name="title"
                                                                    placeholder="Nombre del documento" class="input" />
                                                                @error('title')
                                                                    <span
                                                                        class="text-red-500 text-sm">{{ $message }}</span>
                                                                @enderror
                                                            </label>
                                                            <label class="form-control w-full">
                                                                <div class="label">
                                                                    <span class="label-text">Documento PDF</span>
                                                                </div>
                                                                <input type="file" accept="application/pdf"
                                                                    name="file_path" class="input" />
                                                                @error('file_path')
                                                                    <span
                                                                        class="text-red-500 text-sm">{{ $message }}</span>
                                                                @enderror
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary"
                                                                aria-haspopup="dialog" aria-expanded="false"
                                                                aria-controls="scroll-inside-modal"
                                                                data-overlay="#scroll-inside-modal">Regresar</button>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Subir</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="email"
                                        class="block text-sm font-medium leading-6 text-gray-900">Correo</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $client->email ? '' : 'text-gray-400 italic' }}">
                                            {{ $client->email ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="birthdate" class="block text-sm font-medium leading-6 text-gray-900">Fecha
                                        de
                                        nacimiento</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $client->birthdate ? '' : 'text-gray-400 italic' }}">
                                            {{ $client->birthdate ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>


                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="rfc"
                                        class="block text-sm font-medium leading-6 text-gray-900">RFC</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $client->rfc ? '' : 'text-gray-400 italic' }}">
                                            {{ $client->rfc ?? 'Sin datos existentes' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="curp"
                                        class="block text-sm font-medium leading-6 text-gray-900">CURP</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $client->curp ? '' : 'text-gray-400 italic' }}">
                                            {{ $client->curp ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="phone"
                                        class="block text-sm font-medium leading-6 text-gray-900">Telefono</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $client->phone ? '' : 'text-gray-400 italic' }}">
                                            {{ $client->phone ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="address"
                                        class="block text-sm font-medium leading-6 text-gray-900">Dirección</label>
                                    <div class="mt-2 input input-filled peer" style="max-height: 4rem; overflow-y: auto;">
                                        <p class="{{ $client->address ? '' : 'text-gray-400 italic' }}"
                                            style="word-wrap: break-word;">
                                            {{ $client->address ?? 'Sin datos existentes' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="city"
                                        class="block text-sm font-medium leading-6 text-gray-900">Ciudad</label>
                                    <div class="mt-2 input input-filled peer ">
                                        <p class="{{ $client->city ? '' : 'text-gray-400 italic' }}">
                                            {{ $client->city ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="cp"
                                        class="block text-sm font-medium leading-6 text-gray-900">CP</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $client->cp ? '' : 'text-gray-400 italic' }}">
                                            {{ $client->cp ?? 'sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="state"
                                        class="block text-sm font-medium leading-6 text-gray-900">Estado</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $client->state ? '' : 'text-gray-400 italic' }}">
                                            {{ $client->state ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="regimen "
                                        class="block text-sm font-medium leading-6 text-gray-900">Regimen</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $client->regime_id ? '' : 'text-gray-400 italic ' }}">
                                            {{ $client->regime->title ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>



                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="nss"
                                        class="block text-sm font-medium leading-6 text-gray-900">NSS</label>
                                    <div class="mt-2 input input-filled peer ">
                                        <p class="{{ $client->nss ? '' : 'text-gray-400 italic ' }}">
                                            {{ $client->nss ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="siec"
                                        class="block text-sm font-medium leading-6 text-gray-900">Contraseña SIEC</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $credential->siec ? '' : 'text-gray-400 italic' }}">
                                            {{ $credential->siec ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="curp"
                                        class="block text-sm font-medium leading-6 text-gray-900">Usuario
                                        IDSE</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $credential->useridse ? '' : 'text-gray-400 italic' }}">
                                            {{ $credential->useridse ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="curp"
                                        class="block text-sm font-medium leading-6 text-gray-900">Contraseña IDSE</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $credential->idse ? '' : 'text-gray-400 italic' }}">
                                            {{ $credential->idse ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="curp"
                                        class="block text-sm font-medium leading-6 text-gray-900">Usuario
                                        SIPARE</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $credential->usersipare ? '' : 'text-gray-400 italic' }}">
                                            {{ $credential->usersipare ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="curp"
                                        class="block text-sm font-medium leading-6 text-gray-900">Contraseña SIPARE</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $credential->sipare ? '' : 'text-gray-400 italic' }}">
                                            {{ $credential->sipare ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="note"
                                        class="block text-sm font-medium leading-6 text-gray-900">Nota</label>
                                    <div class="mt-2 input input-filled peer" style="max-height: 4rem; overflow-y: auto;">
                                        <p class="{{ $client->note ? '' : 'text-gray-400 italic' }}"
                                            style="word-wrap: break-word;">
                                            {{ $client->note ?? 'Sin datos existentes' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="token"
                                        class="block text-sm font-medium leading-6 text-gray-900">Token</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $client->token ? '' : 'text-gray-400 italic' }}">
                                            {{ $client->token ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="iniciofiel"
                                        class="block text-sm font-medium leading-6 text-gray-900">Fecha de inicio
                                        (FIEL)</label>
                                    <div class="mt-2 input input-filled peer" style="max-height: 4rem; overflow-y: auto;">
                                        <p class="{{ $client->credentials->iniciofiel ? '' : 'text-gray-400 italic' }}">
                                            {{ $client->credentials->iniciofiel ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="finfiel" class="block text-sm font-medium leading-6 text-gray-900">Fecha
                                        de vencimiento (FIEL)</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $client->credentials->finfiel ? '' : 'text-gray-400 italic' }}">
                                            {{ $client->credentials->finfiel ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="iniciofiel"
                                        class="block text-sm font-medium leading-6 text-gray-900">Fecha de inicio
                                        (SELLO)</label>
                                    <div class="mt-2 input input-filled peer" style="max-height: 4rem; overflow-y: auto;">
                                        <p class="{{ $client->credentials->iniciosello ? '' : 'text-gray-400 italic' }}">
                                            {{ $client->credentials->iniciosello ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="finsello" class="block text-sm font-medium leading-6 text-gray-900">Fecha
                                        de vencimiento (SELLO)</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $client->credentials->finsello ? '' : 'text-gray-400 italic' }}">
                                            {{ $client->credentials->finsello ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="auxone" class="block text-sm font-medium leading-6 text-gray-900">Extra
                                        1</label>
                                    <div class="mt-2 input input-filled peer" style="max-height: 4rem; overflow-y: auto;">
                                        <p class="{{ $client->credentials->auxone ? '' : 'text-gray-400 italic' }}"
                                            style="word-wrap: break-word;">
                                            {{ $client->credentials->auxone ?? 'Sin datos existentes' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="auxtwo" class="block text-sm font-medium leading-6 text-gray-900">Extra
                                        2</label>
                                    <div class="mt-2 input input-filled peer">
                                        <p class="{{ $client->credentials->auxtwo ? '' : 'text-gray-400 italic' }}">
                                            {{ $client->credentials->auxtwo ?? 'Sin datos existentes' }} </p>
                                    </div>
                                </div>


                                <div class="sm:col-span-3 form-control w-full sm:w-96">
                                    <label for="auxthree" class="block text-sm font-medium leading-6 text-gray-900">Extra
                                        3</label>
                                    <div class="mt-2 input input-filled peer" style="max-height: 4rem; overflow-y: auto;">
                                        <p class="{{ $client->credentials->auxthree ? '' : 'text-gray-400 italic' }}"
                                            style="word-wrap: break-word;">
                                            {{ $client->credentials->auxthree ?? 'Sin datos existentes' }}
                                        </p>
                                    </div>
                                </div>



                            </div>
                        </div>

                        <div class="mt-6 pb-2 flex items-center justify-between">
                    <flux:button id="showReceipts" class="cursor-pointer" value="Ver Clientes">Ver Clientes</flux:button>
                    <div class="flex gap-4 items-center">
                        {{-- <flux:icon.trash variant="solid" class="text-red-500" /> --}}
                        <flux:button icon="trash" class="cursor-pointer text-red-500 fill-red-500 bg-red-500"/>
                        <a href="{{ url()->previous() }}">
                            <flux:button class="cursor-pointer">Cancelar</flux:button>
                        </a>
                        <flux:button variant="primary" href="{{ route('client.edit', $client->id) }}">Editar</flux:button>
                    </div>
                </div>
                

                        <div class="hidden" id="tableClients">
                            <livewire:receipt-table :client="$client->id" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.getElementById('showReceipts').addEventListener('click', function() {
            const clients = document.getElementById('tableClients');
            const verClientes = document.getElementById('showReceipts');
            if (clients.classList.contains('hidden')) {
                clients.classList.remove('hidden');
                verClientes.value = "Ocultar Recibos";

            } else {
                clients.classList.add('hidden');
                verClientes.value = "Ver Recibos";
            }
        });

        function confirmDelete(event) {
            event.preventDefault(); // Evita que el formulario se envíe automáticamente

            if (confirm("¿Estás seguro de que deseas eliminar este Cliente?")) {
                event.target.submit();
            }
        }
    </script>
</x-layouts.app>
