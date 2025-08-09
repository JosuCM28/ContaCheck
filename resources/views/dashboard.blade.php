<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="absolute inset-0 p-8 flex flex-col justify-between">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-md">Recibos generados en el mes</p>
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
                            <p class="text-md">Monto total de recibos en el mes</p>
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
                            <p class="text-md">Clientes nuevos en el mes</p>
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
                <livewire:upcoming-credentials />
            </div>            

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-md overflow-y-auto max-h-[500px]">
                <livewire:task-actions />
                <h3 class="text-md mb-4 flex items-center gap-2">
                    <flux:icon.list-bullet class="size-4" />
                    <span>Tareas por hacer</span>
                </h3>

                <ul class="space-y-3">
                    @foreach ($tasks as $task)
                        <li
                            class="relative group flex items-start gap-3 rounded-xl border border-gray-200 bg-white p-4 hover:bg-yellow-50/40 hover:border-yellow-200 transition-colors"
                            id="task-li-{{ $task->id }}"
                        >
                            <span class="pointer-events-none absolute left-0 top-0 h-full w-1 rounded-l-xl bg-yellow-500/70"></span>
                            <input 
                                type="checkbox" 
                                onchange="window.Livewire.dispatch('markAsDone', { 0: {{ $task->id }} })" 
                                class="peer mt-1.5 shrink-0 accent-yellow-600 rounded cursor-pointer"
                                style="transform: scale(1.4);"
                            >
                            <div class="flex-1 min-w-0">

                                <p class="text-sm font-semibold text-gray-900 truncate peer-checked:text-gray-500 peer-checked:line-through">
                                    {{ $task->title }}
                                </p>

                                <div class="mt-1 flex items-center gap-2 text-xs text-gray-600">
                                    {{-- Avatar inicial --}}
                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gray-100 text-gray-700 font-medium">
                                        {{ strtoupper(mb_substr($task->counter_name, 0, 1)) }}
                                    </span>
                                    <span class="truncate">
                                        Para: <span class="font-medium text-gray-800">{{ $task->counter_name }}</span>
                                    </span>
                                </div>


                                <div class="mt-2 flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 text-yellow-700 px-2 py-0.5 text-[11px] font-medium">
                                        <span class="inline-block h-1.5 w-1.5 rounded-full bg-current"></span>
                                        Por hacer
                                    </span>

                                    <span class="text-[11px] text-gray-500">
                                        {{ $task->created_at->locale('es')->translatedFormat('d M') }}
                                    </span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
    <script>
        window.addEventListener('task-done', (e) => {
            const id = e.detail.id;
            const li = document.getElementById('task-li-' + id);
            if (!li) return;
            li.style.transition = 'opacity 200ms ease, transform 200ms ease';
            li.style.opacity = '0';
            li.style.transform = 'translateX(6px)';
            setTimeout(() => li.remove(), 200);
        });
    </script>
</x-layouts.app>
