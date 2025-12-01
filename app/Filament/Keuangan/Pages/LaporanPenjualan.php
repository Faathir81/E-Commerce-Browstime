<?php

namespace App\Filament\Keuangan\Pages;

use App\Exports\LaporanPenjualanExport;
use App\Models\User;
use App\Models\Pembayaran;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Page;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use BackedEnum;
use UnitEnum;

class LaporanPenjualan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Laporan Penjualan';
    protected static ?string $title = 'Laporan Penjualan';
    protected static string | UnitEnum | null $navigationGroup = 'Laporan';

    /**
     * State form filter.
     */
    public ?array $data = [];

    /**
     * Data untuk tabel & summary.
     */
    public $records;
    public array $summary = [];

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->hasRole('keuangan') ?? false;
    }

    public function mount(): void
    {
        // Default filter: bulan berjalan
        $today = Carbon::today();
        $this->data = [
            'start_date' => $today->copy()->startOfMonth()->toDateString(),
            'end_date'   => $today->copy()->endOfMonth()->toDateString(),
        ];

        $this->form->fill($this->data);

        $this->reloadData();
    }

    protected function filterFields(): array
    {
        return [
            DatePicker::make('start_date')
                ->label('Tanggal Mulai')
                ->native(false)
                ->displayFormat('d/m/Y')
                ->closeOnDateSelection()
                ->required(),

            DatePicker::make('end_date')
                ->label('Tanggal Selesai')
                ->native(false)
                ->displayFormat('d/m/Y')
                ->closeOnDateSelection()
                ->required(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Filter Tanggal')
                    ->schema($this->filterFields())
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Filter')
                ->schema([
                    Form::make()
                        ->schema([
                            Grid::make(2)->schema($this->filterFields()),
                        ])
                        ->statePath('data')
                        ->livewireSubmitHandler('applyFilter')
                        ->footer([
                            Action::make('applyFilter')
                                ->label('Terapkan Filter')
                                ->color('primary')
                                ->action('applyFilter'),
                            Action::make('exportExcel')
                                ->label('Export Excel')
                                ->color('gray')
                                ->action('exportExcel'),
                        ]),
                ])
                ->columnSpanFull(),

            Section::make('Ringkasan')
                ->schema([
                    Grid::make(['md' => 3])->schema([
                        TextEntry::make('total_revenue')
                            ->label('Total Pendapatan')
                            ->state(fn () => $this->summary['total_revenue'] ?? 0)
                            ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                        TextEntry::make('transaction_count')
                            ->label('Jumlah Transaksi')
                            ->state(fn () => $this->summary['transaction_count'] ?? 0),
                        TextEntry::make('average_per_order')
                            ->label('Rata-rata per Transaksi')
                            ->state(fn () => $this->summary['average_per_order'] ?? 0)
                            ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                    ]),
                ])
                ->columnSpanFull(),

            Section::make('Detail Penjualan')
                ->schema([
                    RepeatableEntry::make('records')
                        ->label('')
                        ->state(fn () => $this->records?->all() ?? [])
                        ->table([
                            TableColumn::make('Tgl Pembayaran'),
                            TableColumn::make('Kode Pesanan'),
                            TableColumn::make('Customer'),
                            TableColumn::make('Metode'),
                            TableColumn::make('Total Pesanan'),
                            TableColumn::make('Jumlah Bayar'),
                            TableColumn::make('Status Pembayaran'),
                            TableColumn::make('Status Pesanan'),
                        ])
                        ->schema([
                            TextEntry::make('tanggal_pembayaran')
                                ->state(fn ($record) => Carbon::parse($record->tanggal_pembayaran)->format('d/m/Y H:i')),
                            TextEntry::make('kode_pesanan'),
                            TextEntry::make('customer')
                                ->state(fn ($record) => $record->nama_customer ?? $record->guest_email ?? 'Guest'),
                            TextEntry::make('metode')
                                ->formatStateUsing(fn ($state) => ucfirst($state)),
                            TextEntry::make('total_pesanan')
                                ->state(fn ($record) => $record->total_pesanan ?? 0)
                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                            TextEntry::make('jumlah')
                                ->state(fn ($record) => $record->jumlah ?? 0)
                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                            TextEntry::make('status_pembayaran')
                                ->badge()
                                ->color(fn ($state) => match ($state) {
                                    'valid' => 'success',
                                    'pending' => 'warning',
                                    default => 'danger',
                                })
                                ->formatStateUsing(fn ($state) => ucfirst($state)),
                            TextEntry::make('status_pesanan')
                                ->badge()
                                ->color('gray')
                                ->formatStateUsing(fn ($state) => ucfirst($state)),
                        ])
                        ->placeholder('Tidak ada data pada rentang tanggal yang dipilih.'),
                ])
                ->columnSpanFull(),
        ]);
    }

    public function applyFilter(): void
    {
        $this->reloadData();
    }

    protected function reloadData(): void
    {
        $start = !empty($this->data['start_date'])
            ? Carbon::parse($this->data['start_date'])->startOfDay()
            : null;

        $end = !empty($this->data['end_date'])
            ? Carbon::parse($this->data['end_date'])->endOfDay()
            : null;

        $query = Pembayaran::query()
            ->join('pesanans', 'pembayarans.pesanan_id', '=', 'pesanans.id')
            ->leftJoin('users', 'pesanans.user_id', '=', 'users.id')
            ->where('pembayarans.status', 'valid');

        if ($start && $end) {
            $query->whereBetween('pembayarans.created_at', [$start, $end]);
        }

        $this->records = $query
            ->select([
                'pembayarans.id',
                'pembayarans.metode',
                'pembayarans.jumlah',
                'pembayarans.status as status_pembayaran',
                'pembayarans.created_at as tanggal_pembayaran',
                'pesanans.kode as kode_pesanan',
                'pesanans.status as status_pesanan',
                'pesanans.total as total_pesanan',
                'users.name as nama_customer',
                'pesanans.guest_email',
            ])
            ->orderBy('pembayarans.created_at')
            ->get();

        $totalRevenue = $this->records->sum('jumlah');
        $transactionCount = $this->records->count();

        $this->summary = [
            'total_revenue'      => $totalRevenue,
            'transaction_count'  => $transactionCount,
            'average_per_order'  => $transactionCount > 0 ? $totalRevenue / $transactionCount : 0,
        ];
    }

    public function exportExcel()
    {
        $start = !empty($this->data['start_date'])
            ? Carbon::parse($this->data['start_date'])->startOfDay()
            : null;

        $end = !empty($this->data['end_date'])
            ? Carbon::parse($this->data['end_date'])->endOfDay()
            : null;

        return Excel::download(
            new LaporanPenjualanExport($start, $end),
            'laporan-penjualan-' . now()->format('Ymd_His') . '.xlsx'
        );
    }
}
