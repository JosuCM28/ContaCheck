<x-layouts.app :title="__('Sellos')" :subheading="__('Fechas de vencimiento (SELLO)')" >
<div class="relative mb-6 w-full">
   
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="mx-auto p-14">
                    <div class="flex flex-col">

                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div>
                                @if (@session('success'))
                                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4"
                                        role="alert">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <h2 class="text-lg font-bold mb-3">Sello IMSS</h2>
                                <div class="flex justify-between mb-8 items-center">
                                    <p class="text-gray-500">Vencimiento de Sellos</p>
                                    
                                </div>
                            </div>
                            <div>
                                <livewire:sello-table />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </x-layouts.app>