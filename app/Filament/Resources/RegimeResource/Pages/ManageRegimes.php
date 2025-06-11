<?php

namespace App\Filament\Resources\RegimeResource\Pages;

use App\Filament\Resources\RegimeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageRegimes extends ManageRecords
{
    protected static string $resource = RegimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
