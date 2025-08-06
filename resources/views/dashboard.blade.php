<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-[calc(100vh-112px)] w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
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
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
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
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
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
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white p-6 overflow-y-auto max-h-[500px] shadow">
                <h3 class="text-base font-semibold text-neutral-800 mb-4">Últimos Recibos</h3>
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">Cliente</th>
                            <th class="px-4 py-2 text-left font-semibold">Fecha</th>
                            <th class="px-4 py-2 text-left font-semibold">Estado</th>
                            <th class="px-4 py-2 text-left font-semibold">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($ultimosRecibos as $recibo)
                            <tr>
                                <td class="px-4 py-2">{{ $recibo->client->full_name }}</td>
                                <td class="px-4 py-2">{{ $recibo->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">
                                    <span
                                        class="inline-block px-2 py-1 text-xs font-medium rounded 
                            {{ $recibo->status === 'PAGADO'
                                ? 'bg-green-100 text-green-700'
                                : ($recibo->status === 'CANCELADO'
                                    ? 'bg-red-100 text-red-700'
                                    : 'bg-gray-100 text-gray-700') }}">
                                        {{ ucfirst(strtolower($recibo->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 font-semibold text-right">${{ number_format($recibo->mount, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div
                class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white p-6 overflow-y-auto max-h-[500px] shadow">
                <div class="flex items-center justify-center w-full h-full">Centrado</div>
            </div>
        </div>
    </div>
</x-layouts.app>
