<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReceiptResource\Pages;
use App\Models\Receipt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReceiptResource extends Resource
{
    protected static ?string $model = Receipt::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Recibos';
    protected static ?string $navigationGroup = 'Facturación'; // Opcional: agrupar en el menú

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('counter_id')
                    ->label('Contador')
                    ->relationship('counter', 'name') // Asume que 'name' es el campo a mostrar en Counter
                    ->required(),
                Forms\Components\Select::make('client_id')
                    ->label('Cliente')
                    ->relationship('client', 'name') // Asume que 'name' es el campo a mostrar en Client
                    ->required(),
                Forms\Components\Select::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name') // Asume que 'name' es el campo a mostrar en Category
                    ->required(),
                Forms\Components\TextInput::make('identificator')
                    ->label('Identificador')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\DateTimePicker::make('payment_date')
                    ->label('Fecha de Pago')
                    ->required(),
                Forms\Components\Select::make('pay_method')
                    ->label('Método de Pago')
                    ->options([
                        'PUE' => 'Pago en Una Exhibición (PUE)',
                        'PPD' => 'Pago en Parcialidades o Diferido (PPD)',
                        // Agrega más métodos de pago según el SAT
                    ])
                    ->required(),
                Forms\Components\TextInput::make('mount')
                    ->label('Monto')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->minValue(0),
                Forms\Components\Textarea::make('concept')
                    ->label('Concepto')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\Select::make('status')
                    ->label('Estatus')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'pagado' => 'Pagado',
                        'cancelado' => 'Cancelado',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('sello')
                    ->label('Sello')
                    ->maxLength(255)
                    ->nullable(),
            ]);
    }

   public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('identificator')
                ->label('Identificador')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('counter.name')
                ->label('Contador')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('client.name')
                ->label('Cliente')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('category.name')
                ->label('Categoría')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('payment_date')
                ->label('Fecha de Pago')
                ->dateTime()
                ->sortable(),
            Tables\Columns\TextColumn::make('pay_method')
                ->label('Método de Pago')
                ->searchable(),
            Tables\Columns\TextColumn::make('mount')
                ->label('Monto')
                ->money('mxn')
                ->sortable(),
            Tables\Columns\TextColumn::make('concept')
                ->label('Concepto')
                ->limit(50)
                ->searchable(),
            Tables\Columns\TextColumn::make('status')
                ->label('Estatus')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('sello')
                ->label('Sello')
                ->limit(20)
                ->default('-'), // Muestra '-' si el valor es null
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->label('Estatus')
                ->options([
                    'pendiente' => 'Pendiente',
                    'pagado' => 'Pagado',
                    'cancelado' => 'Cancelado',
                ]),
            Tables\Filters\SelectFilter::make('pay_method')
                ->label('Método de Pago')
                ->options([
                    'PUE' => 'Pago en Una Exhibición (PUE)',
                    'PPD' => 'Pago en Parcialidades o Diferido (PPD)',
                ]),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReceipts::route('/'),
            'create' => Pages\CreateReceipt::route('/create'),
            'edit' => Pages\EditReceipt::route('/{record}/edit'),
        ];
    }
}