<?php

namespace App\Filament\Admin\Resources\Pesanans\Schemas;

use App\Support\StatusStyle;
use Filament\Schemas\Schema;
use Filament\Infolists;
use Filament\Schemas\Components\Section;

class PesananInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pesanan')
                    ->schema([
                        Infolists\Components\TextEntry::make('kode')->label('Kode Pesanan'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn (?string $state) => StatusStyle::pesanan($state)['label'])
                            ->color(fn (?string $state) => StatusStyle::pesanan($state)['color'])
                            ->icon(fn (?string $state) => StatusStyle::pesanan($state)['icon']),
                        Infolists\Components\TextEntry::make('total')->money('IDR'),
                        Infolists\Components\TextEntry::make('created_at')->dateTime('d M Y H:i'),
                    ])
                    ->columns(2),
            ]);
    }
}
