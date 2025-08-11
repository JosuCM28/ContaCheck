<div x-data>
    @php
        $badgeColors = [
            'todo' => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'done' => 'bg-green-100 text-green-800',
        ];
    @endphp

    <div id="kanban-board" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
        @foreach ($this->columns as $status => $label)
            <div class="bg-[#f9fafb] rounded-xl p-4 flex flex-col" wire:key="col-{{ $status }}">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-700">{{ $label }}</h2>
                        <span
                            class="text-xs font-medium px-2 py-0.5 rounded-full {{ $badgeColors[$status] ?? 'bg-gray-200 text-gray-700' }}"
                            data-badge="{{ $status }}">
                            {{ ($this->tasks[$status] ?? collect())->count() }}
                        </span>
                    </div>
                    <flux:modal.trigger name="create-task">
                        <button
                            class="bg-white rounded-full hover:bg-gray-50 p-1.5 shadow-sm border text-gray-600 cursor-pointer create-task-btn"
                            title="Nueva tarea" data-create-status="{{ $status }}"
                            data-create-status-label="{{ $label }}">
                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </flux:modal.trigger>
                </div>

                <div class="task-container space-y-2" data-status="{{ $status }}">
                    @forelse ($tasks[$status] as $task)
                        <div class="task bg-white p-4 rounded-xl shadow-sm cursor-pointer space-y-4 border border-gray-200"
                            data-id="{{ $task->id }}" data-title="{{ $task->title }}"
                            data-description="{{ $task->description }}" data-status="{{ $status }}"
                            data-status-label="{{ $label }}" data-counter="{{ $task->counter_name }}"
                            data-created-at="{{ $task->created_at->locale('es')->translatedFormat('d F Y') }}"
                            wire:key="task-{{ $status }}-{{ $task->id }}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="text-sm font-semibold text-gray-900 leading-tight">
                                    {{ $task->title }}
                                </div>
                                <span
                                    class="inline-flex items-center gap-1 text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-600">
                                    <span
                                        class="inline-block size-2 rounded-full {{ $status === 'done' ? 'bg-green-500' : ($status === 'in_progress' ? 'bg-yellow-500' : 'bg-blue-500') }}"></span>
                                    {{ $label }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <p><span class="font-bold">Para:</span> {{ $task->counter_name }}</p>
                                <div class="flex items-center gap-1">
                                    {{ $task->created_at->locale('es')->translatedFormat('d M') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="flex flex-col items-center justify-center p-6 text-gray-400 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            <flux:icon.information-circle class="w-8 h-8 mb-2" />
                            <span class="text-sm">No hay tareas en esta columna</span>
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    <flux:modal name="create-task" class="md:w-140">
        <div class="space-y-6">

            <div>
                <flux:heading size="lg">Nueva tarea</flux:heading>
                <flux:text class="mt-2">Ingresa los datos de la nueva tarea</flux:text>
            </div>
            <flux:field>
                <flux:label>Tarea</flux:label>
                <flux:input wire:model.defer="newTask.title" placeholder="Escribe la tarea.." required />
            </flux:field>

            <flux:field>
                <flux:label>Descripción</flux:label>
                <flux:textarea wire:model.defer="newTask.description" placeholder="Acudir a CFE..." />
            </flux:field>



            <input type="hidden" wire:model="newTask.status" id="create-status">

            <flux:field>
                <flux:label>Asignar a</flux:label>
                <flux:select wire:model.defer="newTask.counter" placeholder="Selecciona un contador">
                    @foreach ($this->counters as $counter)
                        <flux:select.option value="{{ \Illuminate\Support\Str::title($counter->full_name) }}">
                            {{ \Illuminate\Support\Str::title($counter->full_name) }}
                        </flux:select.option>
                    @endforeach
                    <flux:select.option value="Todos">Todos</flux:select.option>
                </flux:select>
            </flux:field>

            <div class="flex justify-between items-center">
                {{-- Recordatorio del estado destino --}}
                <div id="create-status-banner" class="hidden">
                    <div class="flex items-center gap-2 text-sm">
                        <span class="text-gray-600">Se creará en:</span>
                        <span id="create-status-pill"
                            class="inline-flex items-center gap-2 text-xs px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">
                            <span class="inline-block w-2 h-2 rounded-full bg-current opacity-75"></span>
                            <span id="create-status-label">Por hacer</span>
                        </span>
                    </div>
                </div>

                <div>
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancelar</flux:button>
                    </flux:modal.close>

                    <flux:button wire:click="createTask" variant="primary" class="cursor-pointer">
                        Guardar
                    </flux:button>
                </div>
            </div>
        </div>
    </flux:modal>

    <flux:modal.trigger name="show-task">
        <button id="open-show-task" type="button" class="hidden"></button>
    </flux:modal.trigger>

    <flux:modal name="show-task" class="md:w-140">
        <div class="space-y-6">
            <div>
                <flux:heading id="show-title" size="lg">Tarea</flux:heading>

            </div>

            <div class="px-4">
                <div id="show-description" class="my-4 text-sm leading-relaxed whitespace-pre-line text-gray-800">
                    Sin descripción
                </div>

                <div class="border-b border-gray-900/10"></div>

                <div class="flex items-center gap-4 my-4">
                    <flux:icon.clock class="size-4 text-gray-500" />
                    <span id="show-status" class="inline-flex items-center gap-2 text-xs px-2.5 py-1 rounded-full">
                        <span class="inline-block w-2 h-2 rounded-full bg-current opacity-75"></span>
                        <span id="show-status-label">Estado</span>
                    </span>
                    <span id="show-created" class="text-sm text-gray-500">—</span>
                </div>

                <div class="border-b border-gray-900/10"></div>

                <div class="my-4 flex items-center gap-2">
                    <flux:icon.user class="size-4 text-gray-500" />
                    <div>
                        <div class="text-xs text-gray-500 mb-1">Asignada a</div>
                        <div id="show-counter" class="text-sm font-medium">—</div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">

                <button
                    class="inline-flex items-center gap-2 text-sm font-medium text-red-600 hover:text-red-700 cursor-pointer"
                    id="show-delete">
                    <flux:icon.trash class="size-4" id="show-delete-icon" />
                    <flux:icon.loading class="size-4 hidden" id="show-delete-loading" />
                    <span>Eliminar tarea</span>
                </button>

            </div>
        </div>
    </flux:modal>



    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js" defer></script>

    <script>
        let bloquearClick = false;

        function bindSortable(container) {
            // Evita re-bind
            if (container._sortableInstance) return;

            const sortable = new Sortable(container, {
                group: 'kanban',
                draggable: '.task',
                animation: 150,
                distance: 5,
                filter: 'button,a,input,textarea,select',
                preventOnFilter: false,

                onStart: () => {
                    bloquearClick = true;
                    document.body.classList.add('sorting');
                },
                onEnd: (evt) => {
                    document.body.classList.remove('sorting');
                    setTimeout(() => (bloquearClick = false), 0);

                    const noSeMovio = evt.from === evt.to && evt.oldIndex === evt.newIndex;
                    if (noSeMovio) return;

                    const status = evt.to.dataset.status;
                    const ids = Array.from(evt.to.querySelectorAll('.task')).map(n => n.dataset.id);
                    window.Livewire?.dispatch('updateOrder', {
                        status,
                        orderedIds: ids
                    });
                }
            });

            container._sortableInstance = sortable;
        }

        function initKanban() {
            requestAnimationFrame(() => {
                document.querySelectorAll('.task-container').forEach(bindSortable);
            });
        }

        function whenReady(cb) {
            if (window.Sortable && document.querySelector('.task-container')) return cb();
            const iv = setInterval(() => {
                if (window.Sortable && document.querySelector('.task-container')) {
                    clearInterval(iv);
                    cb();
                }
            }, 30);
        }

        document.addEventListener('click', (e) => {
            const card = e.target.closest('.task');
            if (!card || bloquearClick) return;

            const titleEl = document.getElementById('show-title');
            const dateEl = document.getElementById('show-created');
            const pillEl = document.getElementById('show-status');
            const labelEl = document.getElementById('show-status-label');
            const whoEl = document.getElementById('show-counter');
            const descEl = document.getElementById('show-description');

            titleEl.textContent = card.dataset.title || 'Tarea';
            dateEl.textContent = card.dataset.createdAt || '—';
            whoEl.textContent = card.dataset.counter || '—';
            descEl.textContent = card.dataset.description || 'Sin descripción';

            const base = 'inline-flex items-center gap-2 text-xs px-2.5 py-1 rounded-full';
            const map = {
                done: 'bg-green-100 text-green-700',
                in_progress: 'bg-yellow-100 text-yellow-700',
                todo: 'bg-blue-100 text-blue-700'
            };
            pillEl.className = `${base} ${map[card.dataset.status] || map.todo}`;
            labelEl.textContent = card.dataset.statusLabel || card.dataset.status || '—';

            document.getElementById('open-show-task')?.click();

            const deleteBtn = document.getElementById('show-delete');
            if (deleteBtn) {
                deleteBtn.onclick = () => {
                    deleteBtn.disabled = true;
                    deleteBtn.style.pointerEvents = 'none';
                    deleteBtn.style.opacity = '0.6';
                    deleteBtn.querySelector('#show-delete-icon')?.classList.add('hidden');
                    deleteBtn.querySelector('#show-delete-loading')?.classList.remove('hidden');
                    deleteBtn.querySelector('span').textContent = 'Eliminando';
                    window.Livewire?.dispatch('deleteTask', {
                        0: card.dataset.id
                    });
                };
            }
        });

        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.create-task-btn');
            if (!btn) return;

            const status = btn.dataset.createStatus;
            const label = btn.dataset.createStatusLabel || status;

            const hidden = document.getElementById('create-status');
            if (hidden) {
                hidden.value = status;
                hidden.dispatchEvent(new Event('input'));
            }

            const banner = document.getElementById('create-status-banner');
            const pill = document.getElementById('create-status-pill');
            const text = document.getElementById('create-status-label');

            if (banner && pill && text) {
                const base = 'inline-flex items-center gap-2 text-xs px-2.5 py-1 rounded-full';
                const color = status === 'done' ?
                    'bg-green-100 text-green-700' :
                    status === 'in_progress' ?
                    'bg-yellow-100 text-yellow-700' :
                    'bg-blue-100 text-blue-700';
                pill.className = `${base} ${color}`;
                text.textContent = label;
                banner.classList.remove('hidden');
            }
        });

        // -------- Hooks mínimos y seguros --------
        // Primera carga (espera a Sortable y a los contenedores)
        window.addEventListener('DOMContentLoaded', () => whenReady(initKanban));
        window.addEventListener('pageshow', () => whenReady(initKanban)); 

        // Livewire v3: tras inicializar y tras navegar
        window.addEventListener('livewire:load', () => whenReady(initKanban));
        window.addEventListener('livewire:navigated', () => whenReady(initKanban));

        if (window.Livewire?.hook) {
            // cuando Livewire actualiza el DOM del componente
            Livewire.hook('morph.updated', () => whenReady(initKanban));
        }

        // (Opcional) Turbo / Inertia
        document.addEventListener('turbo:load', () => whenReady(initKanban));
        document.addEventListener('inertia:finish', () => whenReady(initKanban));
    </script>

</div>
