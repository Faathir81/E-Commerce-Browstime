<?php

namespace App\Filament\Admin\Resources\Pesanans\Pages;

use App\Filament\Admin\Resources\Pesanans\PesananResource;
use App\Filament\Admin\Resources\Pesanans\Widgets\PesananStatsOverview;
use App\Models\Pesanan;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListPesanans extends ListRecords
{
    protected static string $resource = PesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Pesanan Baru')
                ->icon('heroicon-o-plus'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PesananStatsOverview::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 3;
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge($this->countForStatuses())
                ->badgeColor('gray'),

            'pending' => Tab::make('Menunggu Pembayaran')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending'))
                ->badge($this->countForStatuses(['pending']))
                ->badgeColor('gray'),

            'perlu_perbaikan' => Tab::make('Perlu Perbaikan')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'perlu_perbaikan'))
                ->badge($this->countForStatuses(['perlu_perbaikan']))
                ->badgeColor('warning'),

            'paid' => Tab::make('Terbayar')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'paid'))
                ->badge($this->countForStatuses(['paid']))
                ->badgeColor('info'),

            'produksi' => Tab::make('Diproduksi')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'produksi'))
                ->badge($this->countForStatuses(['produksi']))
                ->badgeColor('info'),

            'shipped' => Tab::make('Dikirim')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'dikirim'))
                ->badge($this->countForStatuses(['dikirim']))
                ->badgeColor('success'),

            'delivered' => Tab::make('Selesai')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'selesai'))
                ->badge($this->countForStatuses(['selesai']))
                ->badgeColor('success'),

            'cancelled' => Tab::make('Batal')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'batal'))
                ->badge($this->countForStatuses(['batal']))
                ->badgeColor('danger'),
        ];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    private function countForStatuses(array $statuses = null): int
    {
        $query = Pesanan::query();

        if ($statuses) {
            $query->whereIn('status', $statuses);
        }

        return $query->count();
    }
}
