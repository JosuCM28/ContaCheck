<div>
    <div class="flex items-center justify-between gap-3 flex-wrap mb-4">
        <h3 class="text-md flex items-center gap-2 text-gray-800">
            <flux:icon.calendar class="size-4" />
            <span>Próximos vencimientos</span>
        </h3>
        <div class="flex items-center gap-2">
            <flux:icon.loading class="size-4 hidden" id="loading" />
            <select class="border rounded-md px-2 py-1 text-sm text-gray-700" wire:model.live="kind" id="kind">
                <option value="all">Todos</option>
                <option value="fiel">FIEL</option>
                <option value="sello">SELLO</option>
            </select>
            <select class="border rounded-md px-2 py-1 text-sm text-gray-700" wire:model.live="window" id="window">
                <option value="7d">7 días</option>
                <option value="1m">1 mes</option>
                <option value="3m">3 meses</option>
                <option value="6m">6 meses</option>
            </select>
        </div>
    </div>

    @forelse ($reminders as $r)
        <a href="{{ $r['client_id'] ? route('client.show', $r['client_id']) : '#' }}"
           class="block p-4 rounded-md shadow-sm mb-2 border-l-4 transition transform hover:scale-102
                {{ $r['due_in'] > 7 ? 'bg-yellow-50 border-yellow-400' : '' }}
                {{ $r['due_in'] <= 7 && $r['due_in'] >= 0 ? 'bg-orange-50 border-orange-500' : '' }}
                {{ $r['due_in'] < 0 ? 'bg-red-50 border-red-500' : '' }}">
            <p class="text-sm text-gray-700 leading-relaxed">
                <span class="font-semibold text-gray-900">{{ $r['client'] }}</span>
                tiene el <strong>{{ $r['type'] }}</strong> por vencer el
                <strong>{{ \Carbon\Carbon::parse($r['end'])->format('d/m/Y') }}</strong>
                <span class="text-gray-500">
                    ({{ \Carbon\Carbon::parse($r['end'])->diffForHumans() }})
                </span>.
            </p>
        </a>
    @empty
        <div class="min-h-80 grid place-items-center">
            <div class="flex flex-col items-center justify-center p-6 text-gray-400">
                <flux:icon.information-circle class="w-8 h-8 mb-2" />
                <span class="text-sm">No hay vencimientos en el rango seleccionado.</span>
            </div>
        </div>
    @endforelse
</div>

<script>
    const loading = document.querySelector('#loading');

    document.querySelector('#kind').addEventListener('change', (e) => {
        loading.classList.remove('hidden');
    });

    document.querySelector('#window').addEventListener('change', (e) => {
        loading.classList.remove('hidden');
    });
</script>
