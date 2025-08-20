<div class="flex items-center min-h-[calc(100vh-160px)] w-full">
    <div class="flex-grow flex items-center justify-center">
        <div class="w-full max-w-4xl mx-auto">

            {{-- ✅ PANTALLA FINAL si ya completó --}}
            @if ($completed)
                <div class="h-[40vw] max-h-[500px] min-h-[200px] flex items-center justify-center">
                    <div class="flex gap-20">
                        {{-- Indicador de estado --}}
                        <div class="flex flex-col items-center">
                            <div class="relative size-28 flex items-center justify-center rounded-full
                                {{ $completed ? 'bg-gray-200' : 'bg-gray-600' }}">
                                @if ($completed)
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg class="animate-spin h-12 w-12 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                    </svg>
                                @endif
                            </div>
                            <h2 class="text-md mt-4 text-gray-800">
                                {{ $completed ? '¡Completado!' : 'Procesando...' }}
                            </h2>
                        </div>

                        {{-- Detalles del proceso --}}
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $fileName }}</h3>
                            </div>

                            <div class="space-y-2 mb-6">
                                <p class="text-md text-gray-600">Tiempo total: {{ $duration }}</p>
                                <p class="text-md text-gray-600">Archivos convertidos: <span class="font-semibold">{{ count($xmls) }}</span></p>
                            </div>

                            @if ($completed)
                                <div class="flex items-center gap-4">
                                    <a href="{{ $downloadLink }}" class="inline-flex items-center px-6 py-2 bg-gray-900 text-white font-medium rounded hover:bg-gray-700 transition" download>
                                        <flux:icon.arrow-down class="w-5 h-5 mr-2" />
                                        Descargar
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


            {{-- 🟢 FORMULARIO PRINCIPAL si no se ha procesado ni completado --}}
            @else
                @if (count($xmls) > 0)
                    <div class="h-[40vw] max-h-[500px] min-h-[200px]">
                        <div class="border border-dashed border-gray-300 bg-white rounded-xl min-h-full">
                            <div class="flex justify-between items-center px-6 py-4 border-b bg-gray-100 rounded-t-xl">
                                <span class="text-gray-700 font-semibold">Nombre</span>
                                <div class="gap-2">
                                    <flux:icon.loading wire:loading wire:target="removeXml" class="size-4 animate-spin" />
                                    <span class="text-sm text-gray-500">Tienes {{ count($xmls) }} archivo{{ count($xmls) > 1 ? 's' : '' }}.</span>
                                </div>
                            </div>
                            <ul class="divide-y overflow-y-auto max-h-[420px]">
                                @foreach ($xmls as $index => $xml)
                                    <li class="flex items-center px-6 py-4">
                                        <flux:icon name="document" class="mr-4 h-6 w-6" />
                                        <span class="text-gray-800 truncate">{{ $xml->getClientOriginalName() }}</span>
                                        <flux:icon.trash variant="mini" wire:click="removeXml({{ $index }})" class="ml-auto cursor-pointer text-red-600 hover:text-red-800 " />
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="py-4 flex justify-between items-center">
                            <div>
                                <input type="file" wire:model="newXmls" multiple accept=".xml" id="fileInput" class="hidden">
                                <label for="fileInput">
                                    <span class="cursor-pointer inline-block px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded">
                                        {{-- Ícono visible solo mientras se suben archivos --}}
                                <span wire:loading wire:target="newXmls">
                                    <flux:icon.loading class="size-4 animate-spin" />
                                </span>

                                {{-- Texto visible siempre --}}
                                <span wire:loading.remove wire:target="newXmls">
                                    + Agregar archivos
                                    </span>
                                </label>
                            </div>

                            <flux:button wire:click="export" class="cursor-pointer">Convertir a Excel</flux:button>
                        </div>
                    </div>
                @else
                    <div class="border border-dashed border-gray-300 rounded-xl text-center bg-gray-50 h-[40vw] max-h-[500px] min-h-[200px] flex flex-col items-center justify-center mx-auto">
                        <p class="text-md text-gray-700 mb-6">
                            {{-- Arrastra aquí tus CFDI o selecciónalos --}}
                        </p>

                        <input type="file" wire:model="newXmls" multiple accept=".xml" id="fileInput" class="hidden">

                        <label for="fileInput">
                            <span class="cursor-pointer inline-flex items-center px-5 py-3 bg-gray-700 hover:bg-gray-900 text-white rounded transition">

                                {{-- Ícono visible solo mientras se suben archivos --}}
                                <span wire:loading wire:target="newXmls">
                                    <flux:icon.loading class="size-4 animate-spin" />
                                </span>

                                {{-- Texto visible siempre --}}
                                <span wire:loading.remove wire:target="newXmls">
                                    Seleccionar XMLs
                                </span>
                            </span>
                        </label>

                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
