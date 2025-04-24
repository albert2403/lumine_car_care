<?php

namespace App\Filament\Resources\CarStoreResource\Pages;

use App\Filament\Resources\CarStoreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarStore extends EditRecord
{
    protected static string $resource = CarStoreResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
