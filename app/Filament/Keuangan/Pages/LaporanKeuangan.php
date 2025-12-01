<?php

namespace App\Filament\Keuangan\Pages;

use App\Exports\LaporanKeuanganExport;
use App\Models\User;
use App\Models\Pembayaran;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
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

class LaporanKeuangan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Laporan Keuangan';
    protected static ?string $title = 'Laporan Keuangan';
    protected static string | UnitEnum | null $navigationGroup = 'Laporan';

    public ?array $data = [];

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
        $today = Carbon::today();
        $this->data = [
            'start_date' => $today->copy()->startOfYear()->toDateString(),
            'end_date'   => $today->copy()->endOfYear()->toDateString(),
            'group_by'   => 'daily',
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
                ->required(),

            DatePicker::make('end_date')
                ->label('Tanggal Selesai')
                ->native(false)
                ->displayFormat('d/m/Y')
                ->required(),

            Select::make('group_by')
                ->label('Group By')
                ->options([
                    'daily'   => 'Harian',
                    'monthly' => 'Bulanan',
                ])
                ->default('daily')
                ->required(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Filter Laporan Keuangan')
                    ->schema($this->filterFields())
                    ->columns([
                        'md' => 3,
                    ]),
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
                            Grid::make(['md' => 3])->schema($this->filterFields()),
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
                        TextEntry::make('grand_total')
                            ->label('Total Pendapatan')
                            ->state(fn () => $this->summary['grand_total'] ?? 0)
                            ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                        TextEntry::make('periode_count')
                            ->label('Jumlah Periode')
                            ->state(fn () => $this->summary['periode_count'] ?? 0),
                        TextEntry::make('average_periode')
                            ->label('Rata-rata Pendapatan per Periode')
                            ->state(fn () => $this->summary['average_periode'] ?? 0)
                            ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                    ]),
                ])
                ->columnSpanFull(),

            Section::make('Detail Pendapatan')
                ->schema([
                    RepeatableEntry::make('records')
                        ->label('')
                        ->state(fn () => $this->records?->all() ?? [])
                        ->table([
                            TableColumn::make(fn () => ($this->data['group_by'] ?? 'daily') === 'monthly' ? 'Bulan' : 'Tanggal'),
                            TableColumn::make('Total Pendapatan'),
                            TableColumn::make('Jumlah Transaksi'),
                            TableColumn::make('Rata-rata per Transaksi'),
                        ])
                        ->schema([
                            TextEntry::make('periode')
                                ->state(function ($record) {
                                    $groupBy = $this->data['group_by'] ?? 'daily';
                                    if ($groupBy === 'monthly') {
                                        return Carbon::createFromFormat('Y-m', $record->periode)->translatedFormat('F Y');
                                    }
                                    return Carbon::parse($record->periode)->format('d/m/Y');
                                }),
                            TextEntry::make('total_revenue')
                                ->state(fn ($record) => $record->total_revenue ?? 0)
                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                            TextEntry::make('jumlah_transaksi')
                                ->state(fn ($record) => $record->jumlah_transaksi ?? 0),
                            TextEntry::make('average_per_transaksi')
                                ->state(fn ($record) => ($record->jumlah_transaksi ?? 0) > 0
                                    ? ($record->total_revenue / $record->jumlah_transaksi)
                                    : 0)
                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                        ])
                        ->placeholder('Tidak ada data keuangan pada rentang tanggal yang dipilih.'),
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

        $groupBy = $this->data['group_by'] ?? 'daily';

        $baseQuery = Pembayaran::query()
            ->where('status', 'valid');

        if ($start && $end) {
            $baseQuery->whereBetween('created_at', [$start, $end]);
        }

        if ($groupBy === 'monthly') {
            $query = $baseQuery
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as periode")
                ->selectRaw('SUM(jumlah) as total_revenue')
                ->selectRaw('COUNT(*) as jumlah_transaksi')
                ->groupBy('periode')
                ->orderBy('periode');
        } else {
            // daily
            $query = $baseQuery
                ->selectRaw('DATE(created_at) as periode')
                ->selectRaw('SUM(jumlah) as total_revenue')
                ->selectRaw('COUNT(*) as jumlah_transaksi')
                ->groupBy('periode')
                ->orderBy('periode');
        }

        $this->records = $query->get();

        $grandTotal = $this->records->sum('total_revenue');
        $periodeCount = $this->records->count();

        $this->summary = [
            'grand_total'       => $grandTotal,
            'periode_count'     => $periodeCount,
            'average_periode'   => $periodeCount > 0 ? $grandTotal / $periodeCount : 0,
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

        $groupBy = $this->data['group_by'] ?? 'daily';

        return Excel::download(
            new LaporanKeuanganExport($start, $end, $groupBy),
            'laporan-keuangan-' . now()->format('Ymd_His') . '.xlsx'
        );
    }
}
