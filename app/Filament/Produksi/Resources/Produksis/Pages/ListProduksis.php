<?php

namespace App\Filament\Produksi\Resources\Produksis\Pages;

use App\Filament\Produksi\Resources\Produksis\ProduksiResource;
use App\Filament\Produksi\Resources\Produksis\Widgets\PesananStatsOverview;
use App\Models\Pesanan;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class ListProduksis extends ListRecords
{
    protected static string $resource = ProduksiResource::class;

    /**
     * Staf produksi tidak boleh create pesanan,
     * jadi kita hilangkan semua header actions.
     */
    protected function getHeaderActions(): array
    {
        return [];
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

    public function getTabsContentComponent(): Component
    {
        $tabs = $this->getCachedTabs();

        return Tabs::make()
            ->livewireProperty('activeTab')
            ->contained(false)
            ->tabs($tabs)
            ->persistTabInQueryString('status')
            ->extraAttributes([
                'class' => 'flex flex-wrap gap-1 bg-gray-900/70 border border-gray-800 rounded-2xl px-3 py-2 w-fit',
            ])
            ->hidden(empty($tabs));
    }

    public function getTabs(): array
    {
        return [
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
        ];
    }

    private function countForStatuses(array $statuses = null): int
    {
        $allowedStatuses = $statuses ?? ProduksiResource::ALLOWED_STATUSES;

        return Pesanan::whereIn('status', $allowedStatuses)->count();
    }
}
