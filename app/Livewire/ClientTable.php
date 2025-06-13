<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Credential;
use App\Models\Regime;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;


#[On('pg:eventRefresh')]
final class ClientTable extends PowerGridComponent
{
    public string $tableName = 'client-table-h66d7c-table';
    use WithExport;
    public int $counter = 0;
    public function __mount(int $counter): void
    {
        $this->counter = $counter;
    }
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
                ->showToggleColumns()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        $client = Client::query()
            ->with('regime')
            ->with('credentials')
            ->addSelect([
                'regime_title' => Regime::select('title')
                    ->whereColumn('regimes.id', 'clients.regime_id')
                    ->limit(1),
                'siec' => Credential::select('siec')
                    ->whereColumn('credentials.id', 'clients.id')
                    ->limit(1),
                'idse' => Credential::select('idse')
                    ->whereColumn('credentials.id', 'clients.id')
                    ->limit(1),
                'sipare' => Credential::select('sipare')
                    ->whereColumn('credentials.id', 'clients.id')
                    ->limit(1),
                'useridse' => Credential::select('useridse')
                    ->whereColumn('credentials.id', 'clients.id')
                    ->limit(1),
                'usersipare' => Credential::select('usersipare')
                    ->whereColumn('credentials.id', 'clients.id')
                    ->limit(1),
                'auxone' => Credential::select('auxone')
                    ->whereColumn('credentials.id', 'clients.id')
                    ->limit(1),
                'auxtwo' => Credential::select('auxtwo')
                    ->whereColumn('credentials.id', 'clients.id')
                    ->limit(1),
                'auxthree' => Credential::select('auxthree')
                    ->whereColumn('credentials.id', 'clients.id')
                    ->limit(1),
                'iniciofiel' => Credential::select('iniciofiel')
                    ->whereColumn('credentials.id', 'clients.id')
                    ->limit(1),
                'finfiel' => Credential::select('finfiel')
                    ->whereColumn('credentials.id', 'clients.id')
                    ->limit(1),
                'iniciosello' => Credential::select('iniciosello')
                    ->whereColumn('credentials.id', 'clients.id')
                    ->limit(1),
                'finsello' => Credential::select('finsello')
                    ->whereColumn('credentials.id', 'clients.id')
                    ->limit(1),
            ]);
        if ($this->counter !== 0) {
            $client->where('counter_id', $this->counter);
        }
        return $client;
    }

    public function relationSearch(): array
    {
        return [
            'regime' => [
                'title'
            ],
            'credentials' => [
                'siec',
                'idse',
                'sipare',
                'useridse',
                'usersipare',
                'auxone',
                'auxtwo',
                'auxthree',
                'iniciofiel',
                'finfiel',
                'iniciosello',
                'finsello',
            ]
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('status')
            ->add('phone')
            ->add('full_name')
            ->add('email')
            ->add('address')
            ->add('rfc')
            ->add('curp')
            ->add('city')
            ->add('state')
            ->add('cp')
            ->add('nss')
            ->add('is_active', fn($item) => $item->status === 'active' ? true : false)
        ;
    }

    public function columns(): array
    {
        return [


            Column::make('Nombre Completo', 'full_name')
                ->sortable()
                ->searchable(),


            Column::make('CURP', 'curp')
                ->sortable()
                ->searchable(),

            Column::make('RFC', 'rfc')
                ->sortable()
                ->searchable(),

            Column::make('SIEC', 'siec')
                ->sortable(),
            Column::make('IDSE', 'idse')
                ->hidden(isHidden: true, isForceHidden: false)
                ->sortable(),
            Column::make('Sipare', 'sipare')
                ->hidden(isHidden: true, isForceHidden: false)
                ->sortable(),
            Column::make('Usuario IDSE', 'useridse')
                ->hidden(isHidden: true, isForceHidden: false)
                ->sortable(),
            Column::make('Usuario Sipare', 'usersipare')
                ->hidden(isHidden: true, isForceHidden: false)
                ->sortable(),
            Column::make('Aux1', 'auxone')
                ->hidden(isHidden: true, isForceHidden: false)
                ->sortable(),
            Column::make('Aux2', 'auxtwo')
                ->hidden(isHidden: true, isForceHidden: false)
                ->sortable(),
            Column::make('Aux3', 'auxthree')
                ->hidden(isHidden: true, isForceHidden: false)
                ->sortable(),
            Column::make('Inicio Fiel', 'iniciofiel')
                ->hidden(isHidden: true, isForceHidden: false)
                ->sortable(),
            Column::make('Fin Fiel', 'finfiel')
                ->hidden(isHidden: true, isForceHidden: false)
                ->sortable(),
            Column::make('Inicio Sello', 'iniciosello')
                ->hidden(isHidden: true, isForceHidden: false)
                ->sortable(),
            Column::make('Fin Sello', 'finsello')
                ->hidden(isHidden: true, isForceHidden: false)
                ->sortable(),

            Column::make('CP', 'cp')
                ->sortable()
                ->hidden(isHidden: true, isForceHidden: false)
                ->searchable(),

            Column::make('Ciudad', 'city')
                ->sortable()
                ->hidden(isHidden: true, isForceHidden: false)
                ->searchable(),

            Column::make('Estado', 'state')
                ->sortable()
                ->hidden(isHidden: true, isForceHidden: false)
                ->searchable(),

            Column::make('NSS', 'nss')
                ->sortable()
                ->hidden(isHidden: true, isForceHidden: false)
                ->searchable(),

            Column::make('Estatus', 'status')
                ->sortable()
                ->hidden(isHidden: true, isForceHidden: false)
                ->searchable(),

            Column::make('Régimen', 'regime_title')
                ->sortable()
                ->hidden(isHidden: true, isForceHidden: false),


            Column::make('Direccion', 'address')
                ->sortable()
                ->hidden(isHidden: true, isForceHidden: false)
                ->searchable(),

            Column::make('Correo', 'email')
                ->sortable()
                ->hidden(isHidden: true, isForceHidden: false)
                ->searchable(),

            Column::make('Telefono', 'phone')
                ->sortable()
                ->hidden(isHidden: true, isForceHidden: false)
                ->searchable(),


            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('regime_title', 'regime_id')
                ->dataSource(Regime::all())
                ->optionLabel('title')
                ->optionValue('id'),

            Filter::select('status', 'status')
                ->dataSource(collect([
                    ['value' => 'active', 'label' => 'Active'],
                    ['value' => 'inactive', 'label' => 'Inactive']
                ]))
                ->optionLabel('label')
                ->optionValue('value'),


        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Client $row): array
    {
        return [
            Button::add('edit')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        ')
                ->id()
                ->class('')
                ->route('client.show', ['client' => $row->id]),
            Button::add('edit')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                        </svg>
                        ')
                ->id()
                ->class('')
                ->route('client.edit', ['client' => $row->id]),
                Button::add('delete')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    ')
                ->id()
                ->class('text-red-500 hover:text-red-700 cursor-pointer')
                ->dispatch('destroyClient', ['id' => $row->id])
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
