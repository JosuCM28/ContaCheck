<div class="flex items-center min-h-[calc(100vh-160px)] w-full">
    <div class="flex-grow flex items-center justify-center">
        <div class="w-full max-w-4xl mx-auto">

            {{-- ✅ PANTALLA FINAL si ya completó --}}
            @if ($completed)
                <div class="h-[40vw] max-h-[500px] min-h-[200px] flex items-center justify-center">
                    <div class="flex gap-20">
                        {{-- Indicador de estado --}}
                        <div class="flex flex-col items-center">
                            <div class="relative size-28 flex items-center justify-center rounded-full bg-gray-200">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" stroke-width="3"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <h2 class="text-md mt-4 text-gray-800">¡Completado!</h2>
                        </div>

                        {{-- Detalles del proceso --}}
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $fileName }}</h3>
                            </div>

                            <div class="space-y-2 mb-6">
                                <p class="text-md text-gray-600">Tiempo total: {{ $duration }}s</p>
                                <p class="text-md text-gray-600">Archivos convertidos:
                                    <span class="font-semibold">{{ count($xmls) }}</span>
                                </p>
                            </div>

                            <div class="flex items-center gap-4">
                                <a href="{{ $downloadLink }}"
                                    class="inline-flex items-center px-6 py-2 bg-gray-900 text-white font-medium rounded hover:bg-gray-700 transition"
                                    download>
                                    <flux:icon.arrow-down class="w-5 h-5 mr-2" />
                                    Descargar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            {{-- 🟢 FORMULARIO PRINCIPAL --}}
            @else
                @if (count($xmls) > 0)
                    <div class="h-[40vw] max-h-[500px] min-h-[200px]">
                        <div class="border border-dashed border-gray-300 bg-white rounded-xl min-h-full">

                            {{-- Cabecera de la lista --}}
                            <div class="flex justify-between items-center px-6 py-4 border-b bg-gray-100 rounded-t-xl">
                                <span class="text-gray-700 font-semibold">Nombre</span>
                                <div class="flex items-center gap-3">
                                    <flux:icon.loading wire:loading wire:target="removeXml"
                                        class="size-4 animate-spin" />
                                    <span class="text-sm text-gray-500">
                                        Tienes {{ count($xmls) }} archivo{{ count($xmls) > 1 ? 's' : '' }}.
                                    </span>
                                </div>
                            </div>

                            {{-- Lista de archivos — limitada a 100 para no saturar el DOM --}}
                            <ul class="divide-y overflow-y-auto max-h-[420px]">
                                @foreach (array_slice($xmls, 0, 100) as $index => $xml)
                                    <li class="flex items-center px-6 py-4">
                                        <flux:icon name="document" class="mr-4 h-6 w-6" />
                                        <span class="text-gray-800 truncate">{{ $xml['name'] }}</span>
                                        <flux:icon.trash variant="mini"
                                            wire:click="removeXml({{ $index }})"
                                            class="ml-auto cursor-pointer text-red-600 hover:text-red-800" />
                                    </li>
                                @endforeach

                                @if (count($xmls) > 100)
                                    <li class="px-6 py-3 text-sm text-gray-500 bg-gray-50 text-center">
                                        ... y {{ count($xmls) - 100 }} archivos más en cola.
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="py-4 flex justify-between items-center">
                            <div>
                                <input type="file" wire:model="newXmls" multiple accept=".xml" id="fileInput"
                                    class="hidden">
                                <label for="fileInput">
                                    <span class="cursor-pointer inline-block px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded">
                                        <span wire:loading wire:target="newXmls">
                                            <flux:icon.loading class="size-4 animate-spin inline" />
                                        </span>
                                        <span wire:loading.remove wire:target="newXmls">+ Agregar archivos</span>
                                    </span>
                                </label>
                            </div>

                            <flux:button wire:click="export"
                                wire:loading.attr="disabled" wire:target="export"
                                class="cursor-pointer">
                                <span wire:loading.remove wire:target="export">Convertir a Excel</span>
                                <span wire:loading wire:target="export" class="flex items-center gap-2">
                                    <flux:icon.loading class="size-4 animate-spin" />
                                    Procesando...
                                </span>
                            </flux:button>
                        </div>
                    </div>

                @else
                    {{-- Zona de drop vacía --}}
                    <div class="rounded-xl border border-gray-200 bg-white" x-data="{ dragging: false }"
                        @dragover.prevent="dragging = true"
                        @dragleave.prevent="dragging = false"
                        @drop.prevent="dragging = false;
                            $refs.fileInput.files = $event.dataTransfer.files;
                            $refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));">

                        <div class="flex items-center px-5 pt-5">
                            <p class="px-2 pt-1 text-sm text-gray-600">
                                Arrastra y suelta tus archivos XML aquí o haz clic para seleccionarlos
                            </p>
                        </div>

                        <div class="p-5">
                            <label for="fileInput"
                                class="w-full cursor-pointer rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 transition
                                       min-h-[200px] max-h-[500px] h-[40vw]
                                       flex flex-col items-center justify-center text-center
                                       hover:bg-gray-50"
                                :class="dragging ? 'ring-2 ring-gray-400 bg-gray-50' : ''">

                                <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-gray-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M12 16V4" stroke-width="1.5" />
                                        <path d="m8 8 4-4 4 4" stroke-width="1.5" />
                                        <path d="M3 16v2a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3v-2" stroke-width="1.5" />
                                    </svg>
                                </div>

                                <div class="space-y-1">
                                    <p class="text-base font-semibold text-gray-800">Selecciona archivos XML</p>
                                    <p class="text-sm text-gray-500">Solo se aceptan archivos .xml de CFDIs</p>
                                </div>

                                <div class="mt-5">
                                    <span class="inline-flex items-center gap-2 rounded-md bg-gray-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-gray-700">
                                        <span wire:loading wire:target="newXmls">
                                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                                            </svg>
                                        </span>
                                        <span wire:loading.remove wire:target="newXmls">Seleccionar Archivos</span>
                                    </span>
                                </div>
                            </label>

                            {{-- Input real (oculto) --}}
                            <input type="file" wire:model="newXmls" multiple accept=".xml" id="fileInput"
                                x-ref="fileInput" class="hidden">
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </div>
</div>
