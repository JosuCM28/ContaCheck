<?php

namespace App\Livewire;

use App\Models\Receipt;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\Counter;
use App\Models\Client;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
final class ReceiptTable extends PowerGridComponent
{
    public string $tableName = 'receipt-table-2gepz9-table';
    use WithExport;
    public int $client = 0;

    public function __mount(int $client): void
    {
        $this->client = $client;
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

        $receipt = Receipt::query()
            ->with('client')
            ->with('category')
            ->with('counter')
            ->addSelect([
                'client_name' => Client::select('full_name')
                    ->whereColumn('clients.id', 'receipts.client_id')
                    ->limit(1),
                'counter_name' => Counter::select('full_name')
                    ->whereColumn('counters.id', 'receipts.counter_id')
                    ->limit(1),
                'category_name' => Category::select('name')
                    ->whereColumn('categories.id', 'receipts.category_id')
                    ->limit(1),
            ])
            ->orderBy('created_at', 'desc');
        if ($this->client !== 0) {
            $receipt->where('client_id', $this->client);
        }
        $filters = request()->input('filters', []);
        $range = data_get($filters, 'payment_date');

        if ($range) {
            $from = $to = null;

            if (is_array($range)) {
                $from = $range['start'] ?? $range['from'] ?? null;
                $to = $range['end'] ?? $range['to'] ?? null;
            } elseif (is_string($range)) {
                // Soporta "YYYY-MM-DD to YYYY-MM-DD", "DD/MM/YYYY to DD/MM/YYYY" y "DD/MM/YYYY to 15"
                // 1) Separa por " to "
                $parts = preg_split('/\s+to\s+/i', trim($range));
                if (count($parts) === 2) {
                    [$from, $to] = [$parts[0], $parts[1]];
                } else {
                    // fallback a " - " o " a "
                    $delim = str_contains($range, ' - ') ? ' - ' : (str_contains($range, ' a ') ? ' a ' : null);
                    if ($delim) {
                        [$from, $to] = explode($delim, $range);
                    }
                }
            }

            // Función local para parsear flexible
            $parse = function (string $s, ?\Carbon\Carbon $base = null) {
                $s = trim($s);
                // Caso "solo día" (p.ej. "15"): usa mes/año del $base
                if (preg_match('/^\d{1,2}$/', $s) && $base instanceof \Carbon\Carbon) {
                    return $base->copy()->day((int) $s)->startOfDay();
                }
                // YYYY-MM-DD
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $s)) {
                    return \Carbon\Carbon::createFromFormat('Y-m-d', $s, config('app.timezone', 'UTC'))->startOfDay();
                }
                // DD/MM/YYYY
                if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $s)) {
                    return \Carbon\Carbon::createFromFormat('d/m/Y', $s, config('app.timezone', 'UTC'))->startOfDay();
                }
                // Último recurso
                return \Carbon\Carbon::parse($s, config('app.timezone', 'UTC'))->startOfDay();
            };

            if ($from && $to) {
                $tzApp = config('app.timezone', 'UTC');

                $fromC = $parse((string) $from)->setTimezone($tzApp)->startOfDay();
                // para "to 15" tomamos día 15 del mismo mes/año que $from
                $toC = $parse((string) $to, $fromC)->setTimezone($tzApp)->endOfDay();

                // Si tu conexión MySQL está en UTC, convierte
                $dbTz = config('database.connections.mysql.timezone');
                if ($dbTz === '+00:00' || $dbTz === 'UTC' || empty($dbTz)) {
                    $fromC = $fromC->utc();
                    $toC = $toC->utc();
                }

                $receipt->whereBetween('payment_date', [$fromC, $toC]);
            }
        }

        return $receipt;

    }

    public function relationSearch(): array
    {
        return [
            'counter' => ['full_name'],
            'client' => ['full_name'],
            'category' => ['name'],


        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('identificator')
            ->add('pay_method')
            ->add('mount')
            ->add('payment_date_formatted', fn(Receipt $model) => Carbon::parse($model->payment_date)->setTimezone(config('app.timezone'))->format('d/m/Y H:i'))
            ->add('status')
            ->add('concept')
            ->add('counter_id')
            ->add('is_timbred')
            ->add('timbrado_name', fn($model) => $model->is_timbred ? 'Timbrado' : 'No timbrado')
            ->add('created_at');

    }

    public function columns(): array
    {
        return [

            Column::add()
                ->title('Contador')
                ->field('counter_name')
                ->hidden(isHidden: true, isForceHidden: false),

            Column::add()
                ->title('Cliente')
                ->field('client_name')
                ->searchable(),

            Column::add()
                ->title('Categoría')
                ->field('category_name'),


            Column::make('Metodo de Pago', 'pay_method')

                ->hidden(isHidden: true, isForceHidden: false)
                ->searchable(),

            Column::make('Monto', 'mount')
                ->searchable(),

            Column::make('Estado', 'status')
                ->sortable()
                ->searchable(),

            Column::make('Concepto', 'concept')
                ->searchable(),

            Column::make('Timbrado', 'timbrado_name', 'is_timbred')
                ->sortable()
                ->hidden(isHidden: true, isForceHidden: false)
                ->searchable(false),

            Column::make('Fecha de Pago', 'payment_date_formatted', 'payment_date')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: false),

            Column::make('Fecha de creación', 'created_at')
                ->sortable()
                ->hidden(isHidden: true, isForceHidden: false)
                ->searchable(),

            Column::action('Acción')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datetimepicker('payment_date')
                ->params([
                    'timezone' => config('app.timezone', 'America/Mexico_City'),
                    'config' => [
                        'mode' => 'range',     // asegura rango
                        'rangeSeparator' => ' to ',      // << coincide con tu UI
                        'altInput' => true,
                        'altFormat' => 'd/m/Y',     // lo que ve el usuario
                        'dateFormat' => 'Y-m-d',     // lo que se envía al backend
                        'enableTime' => false,
                    ],
                ]),
            Filter::select('category_name', 'category_id')
                ->dataSource(Category::all())
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::select('is_timbred', 'is_timbred')
                ->dataSource(collect([
                    ['value' => 1, 'label' => 'Si'],
                    ['value' => 0, 'label' => 'No'],
                ]))
                ->optionLabel('label')
                ->optionValue('value'),
            Filter::select('receipt_status', 'status')
                ->dataSource(Receipt::all())
                ->optionLabel('Estado')
                ->optionValue('id'),
            Filter::select('status', 'status')
                ->dataSource(collect([
                    ['value' => 'PAGADO', 'label' => 'Pagado'],
                    ['value' => 'PENDIENTE', 'label' => 'Pendiente'],
                    ['value' => 'CANCELADO', 'label' => 'Cancelado']
                ]))
                ->optionLabel('label')
                ->optionValue('value'),
            Filter::select('pay_method', 'pay_method')
                ->dataSource(collect([
                    ['value' => 'EFECTIVO', 'label' => 'Efectivo'],
                    ['value' => 'CHEQUE', 'label' => 'Cheque'],
                    ['value' => 'TRANSFERENCIA', 'label' => 'Transferencia Bancaria']
                ]))
                ->optionLabel('label')
                ->optionValue('value'),
            Filter::select('client_id', 'client_id')
                ->dataSource(Client::query()->select('id', 'full_name')->orderBy('full_name')->get())
                ->optionLabel('full_name')
                ->optionValue('id'),


        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Receipt $row): array
    {
        return [
            Button::add('show')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        ')
                ->id()
                ->route('receipt.show', ['identificator' => $row->id]),

            Button::add('edit')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                        </svg>
                        ')
                ->id()
                ->route('receipt.edit', ['receipt' => $row->id]),

            Button::add('download')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        ')
                ->id()
                ->class('')
                ->route('downloadPDF', ['id' => $row->id]),

            Button::add('send')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                    </svg>
                    ')
                ->id()
                ->class('')
                ->confirmPrompt('¿Estas seguro que deseas enviarlo por correo?', 'Enviar')
                ->route('sendPDF', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('edit')
                ->when(fn($row) => $row->status !== 'PENDIENTE')
                ->hide(),

            Rule::button('send')
                ->when(fn() => auth()->user()->rol === 'cliente')
                ->hide(),

            Rule::button('show')
                ->when(fn() => auth()->user()->rol === 'cliente')
                ->hide(),
        ];
    }
}