<?php

namespace App\Filament\Resources\UsocfdiResource\Pages;

use App\Filament\Resources\UsocfdiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsocfdis extends ListRecords
{
    protected static string $resource = UsocfdiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
