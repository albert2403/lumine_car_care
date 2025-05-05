<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarStoreResource\Pages;
use App\Filament\Resources\CarStoreResource\RelationManagers;
use App\Filament\Resources\CarStoreResource\RelationManagers\PhotosRelationManager;
use App\Models\CarService;
use App\Models\CarStore;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarStoreResource extends Resource
{
    protected static ?string $model = CarStore::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->helperText('Gunakan nama layanan yang sesuai dengan bisnis Anda.')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone_number')
                    ->helperText('Masukkan nomor telepon layanan Anda.')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\TextInput::make('cs_name')
                    ->helperText('Masukkan nama customer service layanan Anda.')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Select::make('is_open')
                    ->helperText('Pilih status buka layanan Anda.')
                    ->options([
                        true => 'Open',
                        false => 'Close',
                    ])
                    ->required(),
                
                Forms\Components\Select::make('is_full')
                    ->helperText('Pilih status penuh layanan Anda.')
                    ->options([
                        true => 'Available',
                        false => 'Full Booked',
                    ])
                    ->required(),
                
                Forms\Components\Select::make('city_id')
                ->relationship('city', 'name')
                    ->helperText('Pilih kota layanan Anda.')
                    ->searchable()
                    ->preload()
                    ->required(),
                
                Forms\Components\Repeater::make('storeServices')
                    ->relationship()
                    ->schema([
                        forms\Components\Select::make('car_service_id')
                            ->relationship('service', 'name')
                            ->helperText('Pilih layanan yang tersedia di toko Anda.')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),

                Forms\Components\FileUpload::make('thumbnail')
                    ->helperText('Unggah gambar thumbnail layanan Anda.')
                    ->required()
                    ->image(),

                Forms\Components\Textarea::make('address')
                    ->helperText('Masukkan alamat lengkap layanan Anda.')
                    ->required()
                    ->rows(10)
                    ->cols(20),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable(),

                Tables\Columns\IconColumn::make('is_open')
                    ->label('Status Buka')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_full')
                    ->label('Status Penuh')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->circular()
                    ->size(50)
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('city_id')
                ->label('City')
                    ->relationship('city', 'name'),


                SelectFilter::make('car_service_id')
                ->label('Car Service')
                ->options(CarService::pluck('name', 'id'))
                ->query(function (Builder $query, array $data){
                    if ($data['value']) {
                        $query->whereHas('storeServices', function (Builder $query) use ($data) {
                            $query->where('car_service_id', $data['value']);
                        });
                    }
                }),
                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PhotosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarStores::route('/'),
            'create' => Pages\CreateCarStore::route('/create'),
            'edit' => Pages\EditCarStore::route('/{record}/edit'),
        ];
    }
}
