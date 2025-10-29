<?php

namespace App\Filament\Resources\Categories\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')->label('Name')->searchable()->sortable(),
                TextColumn::make('slug')->label('Slug')->searchable()->sortable(),
                TextColumn::make('created_at')->label('Created')->dateTime('d M Y H:i'),
            ])
            ->filters([])

            // header actions
            ->headerActions([
                CreateAction::make()->label('Add Category'),
            ])

            // Empty state (opsional)
            ->emptyStateHeading('No categories yet')
            ->emptyStateDescription('Create the first category to get started.')
            ->emptyStateActions([
                CreateAction::make()->label('Add Category'),
            ])

            ->searchPlaceholder('Search name or slug...')

            // row actions
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])

            // ⬇️ ganti ini
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
