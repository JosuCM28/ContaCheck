<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Credential;
use App\Models\Regime;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

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
                ->slot('<i class="fa-regular fa-eye" style="color: #306958;"></i>')
                ->id()
                ->class('')
                ->route('client.show', ['client' => $row->id]),
            Button::add('edit')
                ->slot('<i class="icon-[typcn--edit]" style="color:rgb(166, 145, 63);"></i>')
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
