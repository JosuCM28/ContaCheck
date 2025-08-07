<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="absolute inset-0 p-8 flex flex-col justify-between">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-md text-neutral-500">Recibos generados en el mes</p>
                            <h2 class="text-3xl font-bold text-black mt-8">{{ $kpiRecibosMes }}</h2>
                        </div>
                        <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m9 14.25 6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0c1.1.128 1.907 1.077 1.907 2.185ZM9.75 9h.008v.008H9.75V9Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm4.125 4.5h.008v.008h-.008V13.5Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>

                        </div>
                    </div>
                    <div>
                        <a href="#" class="text-md text-blue-600 hover:underline flex items-center gap-1">
                            Ver más
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12.293 4.293a1 1 0 011.414 0L18 8.586a1 1 0 010 1.414l-4.293 4.293a1 1 0 01-1.414-1.414L14.586 10H4a1 1 0 110-2h10.586l-2.293-2.293a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="absolute inset-0 p-8 flex flex-col justify-between">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-md text-neutral-500">Monto total de recibos en el mes</p>
                            <h2 class="text-3xl font-bold text-black mt-8">${{ number_format($kpiMontoTotalMes, 2) }}
                            </h2>
                        </div>
                        <div class="bg-green-100 text-green-600 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                            </svg>

                        </div>
                    </div>
                    <div>
                        <a href="#" class="text-md text-green-600 hover:underline flex items-center gap-1">
                            Ver más
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12.293 4.293a1 1 0 011.414 0L18 8.586a1 1 0 010 1.414l-4.293 4.293a1 1 0 01-1.414-1.414L14.586 10H4a1 1 0 110-2h10.586l-2.293-2.293a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="absolute inset-0 p-8 flex flex-col justify-between">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-md text-neutral-500">Clientes nuevos en el mes</p>
                            <h2 class="text-3xl font-bold text-black mt-8">{{ $kpiClientesNuevos }}</h2>
                        </div>
                        <div class="bg-yellow-100 text-yellow-600 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                            </svg>

                        </div>
                    </div>
                    <div>
                        <a href="#" class="text-md text-yellow-600 hover:underline flex items-center gap-1">
                            Ver más
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12.293 4.293a1 1 0 011.414 0L18 8.586a1 1 0 010 1.414l-4.293 4.293a1 1 0 01-1.414-1.414L14.586 10H4a1 1 0 110-2h10.586l-2.293-2.293a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>   
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 h-full">
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white p-6 shadow max-h-[500px] overflow-y-auto">
                <h3 class="text-md text-neutral-500 mb-4">Próximos vencimientos</h3>

                <div class="space-y-4">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md shadow-sm">
                        <p class="text-sm text-gray-700">
                            <span class="font-semibold">Juan Pérez</span> tiene la <strong>FIEL</strong> por vencer el <strong>12/08/2025</strong> (<span class="text-yellow-600 font-medium">6 días restantes</span>).
                        </p>
                    </div>

                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
                        <p class="text-sm text-gray-700">
                            <span class="font-semibold">María López</span> tiene el <strong>SELLO</strong> <span class="text-red-600 font-medium">vencido</span> desde el <strong>01/08/2025</strong> (hace 5 días).
                        </p>
                    </div>

                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md shadow-sm">
                        <p class="text-sm text-gray-700">
                            <span class="font-semibold">Luis Méndez</span> tiene la <strong>FIEL</strong> vigente hasta el <strong>25/08/2025</strong> (<span class="text-green-600 font-medium">19 días restantes</span>).
                        </p>
                    </div>
                </div>
            </div>            

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white p-6 overflow-y-auto max-h-[500px] shadow">
                <h2 class="text-md mb-4 text-neutral-500">Tareas por hacer</h2>

                <ul class="space-y-3">
                    <li class="flex items-start gap-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <input type="checkbox" class="mt-1 accent-blue-600">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Subir archivos faltantes</p>
                            <p class="text-xs text-gray-500">Cliente: Juan Pérez</p>
                        </div>
                    </li>

                    <li class="flex items-start gap-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <input type="checkbox" class="mt-1 accent-blue-600">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Actualizar RFC de María</p>
                            <p class="text-xs text-gray-500">Revisar CURP y dirección</p>
                        </div>
                    </li>

                    <li class="flex items-start gap-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <input type="checkbox" class="mt-1 accent-blue-600">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Verificar contraseña del SAT</p>
                            <p class="text-xs text-gray-500">Credencial vencida</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-layouts.app>
