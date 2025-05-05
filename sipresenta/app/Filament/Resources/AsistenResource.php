<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsistenResource\Pages;
use App\Filament\Resources\AsistenResource\RelationManagers;
use App\Models\Asisten;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AsistenResource extends Resource
{
    protected static ?string $model = Asisten::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')->required(),
            Forms\Components\TextInput::make('nim')->required(),
            Forms\Components\TextInput::make('rfid_id')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama'),
            Tables\Columns\TextColumn::make('nim'),
            Tables\Columns\TextColumn::make('rfid_id'),
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
            'index' => Pages\ListAsistens::route('/'),
            'create' => Pages\CreateAsisten::route('/create'),
            'edit' => Pages\EditAsisten::route('/{record}/edit'),
        ];
    }
}
