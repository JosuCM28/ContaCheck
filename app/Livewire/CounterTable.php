<?php

namespace App\Livewire;

use App\Models\Counter;
use App\Models\Regime;
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

final class CounterTable extends PowerGridComponent
{
    public string $tableName = 'counter-table-di8mtw-table';
    
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
                ->showToggleColumns()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Counter::query()
            ->with('regime')
            ->addSelect([      
                'regime_title' => Regime::select('title') 
                    ->whereColumn('regimes.id', 'counters.regime_id')
                    ->limit(1) 
            ]);
    }
    
    public function relationSearch(): array
    {
        return [
            'regime' => ['title'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')
            ->add('full_name')
            ->add('last_name')
            ->add('phone')
            ->add('rfc')
            ->add('curp')
            ->add('city')
            ->add('state')
            ->add('cp')
            ->add('nss');
    }

    public function columns(): array
    {
        return [

            Column::make('Nombre Completo', 'full_name')
            ->sortable()
            ->searchable(),

            Column::make('Telefono', 'phone')
                ->sortable()
                ->hidden(isHidden: true, isForceHidden: false)
                ->searchable(),

            Column::make('RFC', 'rfc')
                ->sortable()
                ->searchable(),

            Column::make('CURP', 'curp')
                ->sortable()
                
                ->searchable(),

            Column::make('Ciudad', 'city')
                ->sortable()
                ->hidden(isHidden: true, isForceHidden: false)
                ->searchable(),

            Column::make('Estado', 'state')
                ->sortable()
                ->hidden(isHidden: true, isForceHidden: false)
                ->searchable(),

            Column::make('CP', 'cp')
                ->sortable()
                ->searchable(),

            Column::make('Régimen', 'regime_title')
                ->sortable()
                ->hidden(isHidden: true, isForceHidden: false),
                

            Column::make('Nss', 'nss')
                ->sortable()
                ->hidden(isHidden: true, isForceHidden: false)
                ->searchable(),

            Column::action('Accion')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('regime_title', 'regime_id')
            ->dataSource(Regime::all())
            ->optionLabel('title')
            ->optionValue('id'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Counter $row): array
    {
        return [
            Button::add('read')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#10B981" class="size-5">
  <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
  <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
</svg>
')
                ->id()
                ->class('')
                ->route('counter.show', ['counter' => $row->id]),
                #->dispatch('counter.show', ['counter' => $row->id]),
            Button::add('edit')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#3B82F6" class="size-5" >
  <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
  <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
</svg>
')
                ->id()
                ->class('')
                ->route('counter.edit', ['counter' => $row->id]),
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
