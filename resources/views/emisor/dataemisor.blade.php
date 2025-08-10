<x-layouts.app :title="__('Datos Emisor')">
    <div class="container mx-auto p-12 max-w-7xl">

        <!-- Encabezado -->
        <div class="text-center space-y-1 mb-10">
            <p class="text-2xl font-bold">
                Despacho Contable Baltazar Montes
            </p>
            <p class="text-sm text-gray-500">
                Emisor
            </p>
        </div>

        <flux:separator variant="subtle" class="mb-4" />

        {{-- @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                {{ session('success') }}
            </div>
        @endif --}}

        <div class="p-8">
            <h2 class="text-lg font-semibold text-gray-800">Detalles del emisor</h2>

            <div class="my-10 grid grid-cols-1 sm:grid-cols-6 lg:grid-cols-9 gap-6">
                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Correo</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $company->email ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $company->email ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label for="rfc" class="block text-sm font-medium leading-6 text-gray-900">RFC</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $company->rfc ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $company->rfc ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label for="curp" class="block text-sm font-medium leading-6 text-gray-900">CURP</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $company->curp ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $company->curp ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Teléfono</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $company->phone ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $company->phone ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label for="phone2" class="block text-sm font-medium leading-6 text-gray-900">Teléfono
                        secundario</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $company->phone2 ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $company->phone2 ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label for="nombre_comercial" class="block text-sm font-medium leading-6 text-gray-900">Razon Social</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $company->full_name ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $company->full_name ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label for="street" class="block text-sm font-medium leading-6 text-gray-900">Calle</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $company->street ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $company->street ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label for="num_ext" class="block text-sm font-medium leading-6 text-gray-900">Número
                        Exterior</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $company->num_ext ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $company->num_ext ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label for="col" class="block text-sm font-medium leading-6 text-gray-900">Colonia</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $company->col ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $company->col ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label for="localities"
                        class="block text-sm font-medium leading-6 text-gray-900">Localidad</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $company->localities ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $company->localities ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label for="referer"
                        class="block text-sm font-medium leading-6 text-gray-900">Referencia</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $company->referer ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $company->referer ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label for="city" class="block text-sm font-medium leading-6 text-gray-900">Ciudad</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $company->city ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $company->city ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label for="cp" class="block text-sm font-medium leading-6 text-gray-900">CP</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $company->cp ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $company->cp ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label for="state" class="block text-sm font-medium leading-6 text-gray-900">Estado</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $company->state ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $company->state ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>

                <div class="sm:col-span-3 form-control w-full sm:w-96">
                    <label for="regime_id" class="block text-sm font-medium leading-6 text-gray-900">Régimen
                        Fiscal</label>
                    <div class="mt-2 input input-filled peer">
                        <p class="{{ $company->regime ? '' : 'text-gray-400 italic' }} text-sm">
                            {{ $company->regime->title ?? 'Sin datos existentes' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="border-b border-gray-900/10 pb-12"></div>

            <!-- Botones de acción -->
            <div class="mt-6 pb-2 flex items-center justify-end">
                <div class="flex gap-4 items-center">
                    <a href="{{ url()->previous() }}">
                        <flux:button class="cursor-pointer">Regresar</flux:button>
                    </a>
                    <flux:button variant="primary" href="{{ route('emisor.edit')}}">Editar</flux:button>
                </div>
            </div>

        </div>
    </div>

</x-layouts.app>
