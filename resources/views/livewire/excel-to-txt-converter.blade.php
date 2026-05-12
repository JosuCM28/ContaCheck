<div class="flex items-center min-h-[calc(100vh-160px)] w-full">
    <div class="flex-grow flex items-center justify-center">
        <div class="w-full max-w-3xl mx-auto">

            {{-- ✅ COMPLETADO --}}
            @if ($completed && $downloadLink)
                <div class="py-8 flex flex-col gap-8" x-data="{ ruta: '' }">

                    <div class="flex gap-16 items-start">
                        {{-- Indicador --}}
                        <div class="flex flex-col items-center shrink-0">
                            <div class="size-28 flex items-center justify-center rounded-full bg-gray-200">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h2 class="text-md mt-4 text-gray-800">¡Convertido!</h2>
                        </div>

                        {{-- Resumen --}}
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 truncate">{{ $fileName }}</h3>
                            <div class="space-y-1 mb-6 text-sm text-gray-600">
                                <p>Filas convertidas: <span class="font-semibold">{{ $rowCount }}</span></p>
                            </div>

                            <button wire:click="reset_form" type="button"
                                class="text-sm text-gray-500 hover:text-gray-700 underline underline-offset-2">
                                Convertir otro archivo
                            </button>
                        </div>
                    </div>

                    {{-- Selector de ruta + botones (descarga directa con <a>) --}}
                    <div class="border-t border-gray-100 pt-6">
                        <p class="text-sm text-gray-700 mb-3">Guardar archivo TXT</p>

                        <div class="flex items-center gap-2">
                            <input type="text" x-model="ruta" readonly
                                placeholder="Ruta del archivo…"
                                class="flex-1 border border-gray-200 rounded-md px-3 py-2 text-sm bg-gray-50 text-gray-600 focus:outline-none"/>

                            {{-- Examinar: rellena el campo y descarga --}}
                            <a href="{{ $downloadLink }}" download="{{ $txtName }}"
                                @click="ruta = '{{ $txtName }}'"
                                class="inline-flex items-center px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 transition whitespace-nowrap">
                                Examinar
                            </a>

                            {{-- Guardar: descarga directa --}}
                            <a href="{{ $downloadLink }}" download="{{ $txtName }}"
                                class="inline-flex items-center px-5 py-2 text-sm font-medium text-white bg-gray-900 rounded-md hover:bg-gray-700 transition whitespace-nowrap">
                                Guardar
                            </a>
                        </div>
                    </div>
                </div>

            {{-- 🟢 ZONA DE CARGA --}}
            @else
                <div class="rounded-xl border border-gray-200 bg-white"
                     x-data="{ dragging: false }"
                     @dragover.prevent="dragging = true"
                     @dragleave.prevent="dragging = false"
                     @drop.prevent="dragging = false;
                        $refs.excelInput.files = $event.dataTransfer.files;
                        $refs.excelInput.dispatchEvent(new Event('change', { bubbles: true }));">

                    <div class="flex items-center px-5 pt-5">
                        <p class="px-2 pt-1 text-sm text-gray-600">
                            Sube un archivo Excel para convertirlo automáticamente a TXT con pipes
                        </p>
                    </div>

                    <div class="p-5">
                        <label for="excelInput"
                            class="w-full cursor-pointer rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 transition
                                   min-h-[200px] max-h-[500px] h-[40vw]
                                   flex flex-col items-center justify-center text-center
                                   hover:bg-gray-50"
                            :class="dragging ? 'ring-2 ring-gray-400 bg-gray-50' : ''">

                            <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M12 16V4" stroke-width="1.5"/>
                                    <path d="m8 8 4-4 4 4" stroke-width="1.5"/>
                                    <path d="M3 16v2a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3v-2" stroke-width="1.5"/>
                                </svg>
                            </div>

                            <div class="space-y-1">
                                <p class="text-base font-semibold text-gray-800">Selecciona un archivo Excel</p>
                                <p class="text-sm text-gray-500">Se aceptan archivos .xlsx, .xls o .xlsm</p>
                            </div>

                            <div class="mt-5">
                                <span class="inline-flex items-center gap-2 rounded-md bg-gray-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-gray-700">
                                    <span wire:loading wire:target="excel">
                                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                                        </svg>
                                        Procesando…
                                    </span>
                                    <span wire:loading.remove wire:target="excel">Seleccionar archivo</span>
                                </span>
                            </div>
                        </label>

                        <input type="file" wire:model="excel" accept=".xlsx,.xls,.xlsm" id="excelInput"
                            x-ref="excelInput" class="hidden"/>

                        @error('excel')
                            <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
