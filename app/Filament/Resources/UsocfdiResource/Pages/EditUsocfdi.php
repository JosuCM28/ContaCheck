<?php

namespace App\Filament\Resources\UsocfdiResource\Pages;

use App\Filament\Resources\UsocfdiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUsocfdi extends EditRecord
{
    protected static string $resource = UsocfdiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
