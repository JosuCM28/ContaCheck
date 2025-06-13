<x-layouts.appHome>
    {{-- Navbar --}}
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 md:flex md:items-center md:justify-between py-4">
            <div class="flex items-center justify-between">
                <flux:field>
                    <flux:label>
                        <a href="#" onclick="location.reload()" class="text-2xl font-bold text-gray-800">Despacho Contable BM</a>
                    </flux:label>
                </flux:field>
                <button class="md:hidden focus:outline-none" data-collapse-toggle="menu">
                    <span class="icon-[tabler-menu-2] text-2xl"></span>
                </button>
            </div>
            <div id="menu" class="hidden md:flex md:items-center mt-4 md:mt-0">
                <flux:field>
                    <flux:select placeholder="Opciones" class="md:flex md:gap-4">
                        <flux:select.option>
                            <button type="button" class="px-4 py-2 rounded-lg hover:bg-gray-100 transition" data-overlay="#toggle-docs">Ver Documentos</button>
                        </flux:select.option>
                        <flux:select.option>
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();" class="px-4 py-2 rounded-lg hover:bg-gray-100 transition">Cerrar sesión</x-dropdown-link>
                            </form>
                        </flux:select.option>
                    </flux:select>
                </flux:field>
            </div>
        </div>
    </nav>

    {{-- Header --}}
    <header class="bg-gradient-to-r from-blue-600 to-purple-600 py-12 rounded-lg mx-4 md:mx-0 mt-8">
        <div class="container mx-auto text-center text-white">
            <h1 class="text-3xl font-bold">Bienvenido, {{ $client->full_name }}</h1>
            <p class="mt-2">Historial de Recibos</p>
        </div>
    </header>

    {{-- Dashboard stats --}}
    <section class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 my-8">
        <flux:field>
            <flux:label class="text-center text-gray-600">Total Recibos</flux:label>
            <flux:input readonly class="text-2xl font-semibold text-center" value="{{ $totalReceipts ?? '—' }}" />
        </flux:field>
        <flux:field>
            <flux:label class="text-center text-gray-600">Monto Total</flux:label>
            <flux:input readonly class="text-2xl font-semibold text-center" value="${{ number_format($totalAmount, 2) }}" />
        </flux:field>
        <flux:field>
            <flux:label class="text-center text-gray-600">Pendientes</flux:label>
            <flux:input readonly class="text-2xl font-semibold text-center" value="{{ $pending ?? '0' }}" />
        </flux:field>
    </section>

    {{-- Recibos table --}}
    <section class="container mx-auto bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4">
            <livewire:user-table />
        </div>
    </section>

    {{-- Modal Ver Documentos --}}
    <div id="toggle-docs" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-start pt-20">
        <div class="bg-white rounded-lg w-full max-w-xl mx-4 md:mx-0">
            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="text-xl font-semibold">Documentos</h2>
                <button data-overlay-close data-overlay="#toggle-docs" class="text-gray-600 hover:text-gray-800">
                    <span class="icon-[tabler-x] text-2xl"></span>
                </button>
            </div>
            <div class="p-4 space-y-4 max-h-80 overflow-y-auto">
                @foreach ($client->document as $document)
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="icon-[fa6-solid-file-pdf] text-red-600 text-2xl"></span>
                            <span>{{ $document->title }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('file.download', $document->id) }}" target="_blank" class="p-2 rounded hover:bg-gray-100">
                                <span class="icon-[ic-round-download] text-blue-600 text-xl"></span>
                            </a>
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="p-2 rounded hover:bg-gray-100">
                                <span class="icon-[weui-eyes-on-outlined] text-green-600 text-xl"></span>
                            </a>
                            <form action="{{ route('file.destroy', $document->id) }}" method="post">
                                @method('delete') @csrf
                                <button type="submit" class="p-2 rounded hover:bg-gray-100">
                                    <span class="icon-[tdesign-delete-1] text-red-600 text-xl"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-4 border-t text-right">
                <button data-overlay="#toggle-upload" class="btn btn-primary">Subir PDF</button>
            </div>
        </div>
    </div>

    {{-- Modal Subir PDF --}}
    <div id="toggle-upload" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-start pt-20">
        <div class="bg-white rounded-lg w-full max-w-md mx-4 md:mx-0">
            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="text-xl font-semibold">Subir Documento</h2>
                <button data-overlay-close data-overlay="#toggle-upload" class="text-gray-600 hover:text-gray-800">
                    <span class="icon-[tabler-x] text-2xl"></span>
                </button>
            </div>
            <form action="{{ route('file.store', $client->id) }}" method="post" enctype="multipart/form-data" class="p-4 space-y-4">
                @csrf
                <flux:field>
                    <flux:label>Título</flux:label>
                    <flux:input name="title" type="text" placeholder="Nombre del documento" />
                    <flux:error name="title" />
                </flux:field>
                <flux:field>
                    <flux:label>Documento PDF</flux:label>
                    <flux:input name="file_path" type="file" accept="application/pdf" />
                    <flux:error name="file_path" />
                </flux:field>
                <div class="flex justify-between">
                    <button data-overlay="#toggle-docs" type="button" class="btn btn-secondary">Regresar</button>
                    <button type="submit" class="btn btn-primary">Subir</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-gray-100 py-10 mt-12">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <div class="flex justify-center md:justify-start">
                <img src="{{ asset('img/DESPACHO.png') }}" alt="Logo" class="w-40 h-auto">
            </div>
            <nav class="space-y-2">
                <a href="#inicio" class="block hover:text-blue-600">Inicio</a>
                <a href="#nosotros" class="block hover:text-blue-600">Acerca de</a>
                <a href="#servicios" class="block hover:text-blue-600">Servicios</a>
                <a href="#contacto" class="block hover:text-blue-600">Ubicación y Contacto</a>
            </nav>
            <nav class="space-y-2">
                <h6 class="font-semibold">Páginas de interés</h6>
                <a href="https://www.sat.gob.mx/home" target="_blank" class="block hover:text-blue-600">SAT</a>
                <a href="https://www.sat.gob.mx/empresas/sin-fines-de-lucro/iniciar-sesion" target="_blank" class="block hover:text-blue-600">Buzón Tributario</a>
                <a href="https://www.imss.gob.mx/" target="_blank" class="block hover:text-blue-600">IMSS</a>
            </nav>
            <nav class="space-y-2">
                <h6 class="font-semibold">Contacto</h6>
                <a href="mailto:baltazarmontes77@prodigy.net.mx" class="block hover:text-blue-600">baltazarmontes77@prodigy.net.mx</a>
                <a href="tel:+522263161354" class="block hover:text-blue-600">+52 226 316 13 54</a>
                <a href="tel:+522263160629" class="block hover:text-blue-600">+52 226 316 06 29</a>
            </nav>
        </div>
    </footer>
