<?php

namespace App\Filament\Widgets;

use App\Services\Report\ReportService;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Contracts\Support\Htmlable;

class StockCurrentWidget extends TableWidget
{
    public function getHeading(): string|Htmlable|null
    {
        return 'Lowest Stock Materials';
    }

    public function table(Table $table): Table
    {
        /** @var ReportService $report */
        $report = app(ReportService::class);

        // Ambil 5 stok terendah dari service (boleh array/collection)
        $rows = $report->lowestStock(5);
        $records = is_array($rows) ? $rows : ($rows?->toArray() ?? []);

        return $table
            // Jika tidak pakai summarizer/filters berbasis query, tidak perlu ->query()
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Material'),
                Tables\Columns\TextColumn::make('qty')
                    ->label('Qty')
                    ->numeric(2),
            ])
            ->records(fn (): array => $records);
    }
}
