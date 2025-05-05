<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PraktikanResource\Pages;
use App\Filament\Resources\PraktikanResource\RelationManagers;
use App\Models\Praktikan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PraktikanResource extends Resource
{
    protected static ?string $model = Praktikan::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')->required(),
                Forms\Components\TextInput::make('nim')->required(),
                Forms\Components\TextInput::make('fingerprint_id')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama'),
                Tables\Columns\TextColumn::make('nim'),
                Tables\Columns\TextColumn::make('fingerprint_id'),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPraktikans::route('/'),
            'create' => Pages\CreatePraktikan::route('/create'),
            'edit' => Pages\EditPraktikan::route('/{record}/edit'),
        ];
    }
}
