<?php

namespace App\Filament\Widgets;

use App\Models\Material;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStock extends BaseWidget
{
    protected static ?string $heading = 'Low Stock (Top 5)';

    protected int | string | array $columnSpan = ['md' => 2, 'xl' => 1];

    public function table(Table $table): Table
    {
        $query = Material::query()
            ->select('materials.*')
            ->selectRaw('(SELECT COALESCE(SUM(ms.qty), 0)
                          FROM material_stocks ms
                          WHERE ms.material_id = materials.id) AS current_stock')
            ->whereColumn('current_stock', '<', 'min_qty')
            ->orderByRaw('(min_qty - current_stock) DESC');

        return $table
            ->query(
                Material::query()
                    ->orderBy('min_qty', 'asc')
                    ->limit(5)
            )
            ->paginated(false)
            ->defaultPaginationPageOption(5)
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Material')
                    ->searchable()
                    ->limit(24),

                Tables\Columns\TextColumn::make('current_stock')
                    ->label('Stock')
                    ->formatStateUsing(fn ($state) => rtrim(rtrim(number_format((float) $state, 3, '.', ''), '0'), '.')),

                Tables\Columns\TextColumn::make('min_qty')
                    ->label('Min')
                    ->formatStateUsing(fn ($state) => rtrim(rtrim(number_format((float) $state, 3, '.', ''), '0'), '.')),

                Tables\Columns\TextColumn::make('unit')
                    ->label('Unit')
                    ->sortable(),
            ])
            ->defaultSort('min_qty', 'desc')
            ->heading('Low Stock (Top 5)')
            ->recordUrl(null);
    }

    protected function getHeading(): string
    {
        return 'Low Stock (Top 5)';
    }
}
