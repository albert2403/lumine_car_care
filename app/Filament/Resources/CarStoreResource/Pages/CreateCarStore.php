<?php

namespace App\Filament\Resources\CarStoreResource\Pages;

use App\Filament\Resources\CarStoreResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCarStore extends CreateRecord
{
    protected static string $resource = CarStoreResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
