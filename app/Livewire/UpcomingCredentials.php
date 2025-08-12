<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Credential;
use Illuminate\Support\Collection;

class UpcomingCredentials extends Component
{
    public string $window = '7d';   // 7d | 1m | 3m | 6m
    public string $kind   = 'all';  // all | fiel | sello

    protected function range(): array
    {
        $from = now()->startOfDay();
        $to = match ($this->window) {
            '7d' => $from->copy()->addDays(7),
            '1m' => $from->copy()->addMonth(),
            '3m' => $from->copy()->addMonths(3),
            '6m' => $from->copy()->addMonths(6),
            default => $from->copy()->addDays(7),
        };
        return [$from, $to];
    }

    public function getRemindersProperty(): Collection
    {
        [$from, $to] = $this->range();
        $items = collect();

        if ($this->kind === 'all' || $this->kind === 'fiel') {
            $fiel = Credential::with('client')
                ->whereNotNull('finfiel')
                ->whereBetween('finfiel', [$from, $to])
                ->get()
                ->map(fn ($c) => [
                    'client_id' => $c->client?->id,
                    'client'    => $c->client?->name ?? 'Sin cliente',
                    'type'      => 'FIEL',
                    'start'     => $c->iniciofiel,
                    'end'       => $c->finfiel,
                    'due_in'    => now()->diffInDays($c->finfiel, false),
                ]);
            $items = $items->merge($fiel);
        }

        if ($this->kind === 'all' || $this->kind === 'sello') {
            $sello = Credential::with('client')
                ->whereNotNull('finsello')
                ->whereBetween('finsello', [$from, $to])
                ->get()
                ->map(fn ($c) => [
                    'client_id' => $c->client?->id,
                    'client'    => $c->client?->name ?? 'Sin cliente',
                    'type'      => 'SELLO',
                    'start'     => $c->iniciosello,
                    'end'       => $c->finsello,
                    'due_in'    => now()->diffInDays($c->finsello, false),
                ]);
            $items = $items->merge($sello);
        }

        return $items->sortBy('end')->values();
    }

    public function render()
    {
        return view('livewire.upcoming-credentials', [
            'reminders' => $this->reminders,
        ]);
    }
}
