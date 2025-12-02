<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pesanan;
use App\Support\StatusStyle;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;

class PesananTerbaruTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getHeading(): string
    {
        return 'Pesanan Terbaru';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Pesanan::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('kode')->label('Kode')->searchable(),
                TextColumn::make('nama_pelanggan')->label('Pelanggan'),
                TextColumn::make('total')->label('Total')->money('idr', true),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => StatusStyle::pesanan($state)['label'])
                    ->color(fn (?string $state) => StatusStyle::pesanan($state)['color'])
                    ->icon(fn (?string $state) => StatusStyle::pesanan($state)['icon']),
                TextColumn::make('created_at')->label('Tanggal')->dateTime('d M Y H:i'),
            ])
            ->recordActions([
                Action::make('lihat')
                    ->label('Detail')
                    ->url(fn ($record) => route('filament.admin.resources.pesanans.view', $record))
                    ->color('primary'),
            ]);
    }
}
