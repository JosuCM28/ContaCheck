<?php

namespace App\Livewire;

use App\Models\Credential;
use App\Models\Client;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

final class SelloTable extends PowerGridComponent
{
    public string $tableName = 'sello-table-kyyfkb-table';
    use WithExport;
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('export')
                ->striped()
                ->columnWidth([
                    2 => 30,
                ])
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Credential::query()
            ->with([
                'client' => function ($query) {
                    $query->where(function ($q) {
                        $q->whereNull('user_id')
                            ->orWhere('user_id', '!=', 1);
                    });
                }
            ])
            ->whereHas('client', function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('user_id')
                        ->orWhere('user_id', '!=', 1);
                });
            })
            ->addSelect([
                'client_full_name' => Client::select('full_name')
                    ->whereColumn('clients.id', 'credentials.client_id')
                    ->where(function ($q) {
                        $q->whereNull('clients.user_id')
                            ->orWhere('clients.user_id', '!=', 1);
                    })
                    ->limit(1),
            ]);
    }

    public function relationSearch(): array
    {
        return [
            'client' => ['full_name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('client_id')
            ->add('iniciosello_formatted', fn(Credential $model) => Carbon::parse($model->iniciosello)->format('d/m/Y'))
            ->add('finsello_formatted', fn(Credential $model) => Carbon::parse($model->finsello)->format('d/m/Y'))
            ->add('days_remaining', function (Credential $model) {
                $currentDate = now(); // Fecha actual
                $finsello = Carbon::parse($model->finsello);


                return number_format($currentDate->diffInDays($finsello, false), 2);// `false` para obtener valores negativos
            })
            ->add('status', function (Credential $model) {
                $currentDate = now(); // Fecha actual
                $finfiel = Carbon::parse($model->finsello); // Fecha de fin
                $diferenciaDias = $currentDate->diffInDays($finfiel, false);


                switch (true) {
                    case $diferenciaDias <= 0:
                        return '<span class="bg-red-200 p-2 rounded">Expirado</span>';
                    case $diferenciaDias <= 7:
                        return '<span class="bg-orange-200 p-2 rounded">Pronto expira</span>';
                    default:
                        return '<span class="bg-green-200 p-2 rounded">Vigente</span>';
                }
            });
    }

    public function columns(): array
    {
        return [
            Column::add()
                ->title('Cliente')
                ->field('client_full_name')
                ->sortable()
                ->searchable(),

            Column::make('Fecha de inicio', 'iniciosello_formatted', 'iniciosello')
                ->sortable(),

            Column::make('Fecha de vencimiento', 'finsello_formatted', 'finsello')
                ->sortable(),

            Column::make('Diferencia en Días', 'days_remaining', 'days'),

            Column::make('Status', 'status')
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('iniciosello'),
            Filter::datepicker('finfiel'),
            Filter::datepicker('iniciosello'),
            Filter::datepicker('finsello'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Credential $row): array
    {
        return [
            Button::add('show')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>')
                ->id()
                ->class('')
                ->route('client.show', ['client' => $row->id]),

            Button::add('edit')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                    </svg>')
                ->id()
                ->class('')
                ->route('client.edit', ['client' => $row->id]),
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
