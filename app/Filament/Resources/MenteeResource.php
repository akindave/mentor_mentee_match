<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenteeResource\Pages;
use App\Filament\Resources\MenteeResource\RelationManagers;
use App\Models\Mentee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class MenteeResource extends Resource
{
    protected static ?string $model = Mentee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('user.username')->label('Username'),
                TextColumn::make('user.name')->label('Full Name'),
                TextColumn::make('user.email')->label('Email'),
                ImageColumn::make('user.avatar_url')->label('Avatar')
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListMentees::route('/'),
            'create' => Pages\CreateMentee::route('/create'),
            'edit' => Pages\EditMentee::route('/{record}/edit'),
        ];
    }
}
