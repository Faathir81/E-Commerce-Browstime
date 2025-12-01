<?php

namespace App\Filament\Keuangan\Pages;

use App\Exports\LaporanStokExport;
use App\Models\User;
use App\Models\MutasiStok;
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

class LaporanStok extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Laporan Stok Bahan Baku';
    protected static ?string $title = 'Laporan Stok Bahan Baku';
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
                ->required(),

            DatePicker::make('end_date')
                ->label('Tanggal Selesai')
                ->native(false)
                ->displayFormat('d/m/Y')
                ->required(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Filter Tanggal Mutasi')
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
                        TextEntry::make('stok_masuk')
                            ->label('Total Stok Masuk')
                            ->state(fn () => $this->summary['stok_masuk'] ?? 0)
                            ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                        TextEntry::make('pemakaian_produksi')
                            ->label('Pemakaian Produksi')
                            ->state(fn () => $this->summary['pemakaian_produksi'] ?? 0)
                            ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                        TextEntry::make('total_mutasi')
                            ->label('Total Mutasi')
                            ->state(fn () => $this->summary['total_mutasi'] ?? 0),
                    ]),
                ])
                ->columnSpanFull(),

            Section::make('Mutasi Stok')
                ->schema([
                    RepeatableEntry::make('records')
                        ->label('')
                        ->state(fn () => $this->records?->all() ?? [])
                        ->table([
                            TableColumn::make('Tanggal'),
                            TableColumn::make('Bahan Baku'),
                            TableColumn::make('Jenis Mutasi'),
                            TableColumn::make('Qty'),
                            TableColumn::make('Stok Awal'),
                            TableColumn::make('Stok Akhir'),
                            TableColumn::make('User'),
                            TableColumn::make('Catatan'),
                        ])
                        ->schema([
                            TextEntry::make('created_at')
                                ->state(fn ($record) => Carbon::parse($record->created_at)->format('d/m/Y H:i')),
                            TextEntry::make('nama_bahan'),
                            TextEntry::make('jenis_mutasi')
                                ->badge()
                                ->color('gray')
                                ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucfirst($state))),
                            TextEntry::make('qty')
                                ->state(fn ($record) => $record->qty ?? 0)
                                ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                            TextEntry::make('stok_awal')
                                ->state(fn ($record) => $record->stok_awal ?? 0)
                                ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                            TextEntry::make('stok_akhir')
                                ->state(fn ($record) => $record->stok_akhir ?? 0)
                                ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                            TextEntry::make('nama_user')
                                ->state(fn ($record) => $record->nama_user ?? '-'),
                            TextEntry::make('catatan')
                                ->state(fn ($record) => $record->catatan ?? '-'),
                        ])
                        ->placeholder('Tidak ada mutasi stok pada rentang tanggal yang dipilih.'),
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

        $query = MutasiStok::query()
            ->join('bahan_bakus', 'mutasi_stok.bahan_id', '=', 'bahan_bakus.id')
            ->leftJoin('users', 'mutasi_stok.user_id', '=', 'users.id');

        if ($start && $end) {
            $query->whereBetween('mutasi_stok.created_at', [$start, $end]);
        }

        $this->records = $query
            ->select([
                'mutasi_stok.id',
                'mutasi_stok.created_at',
                'mutasi_stok.jenis_mutasi',
                'mutasi_stok.qty',
                'mutasi_stok.stok_awal',
                'mutasi_stok.stok_akhir',
                'mutasi_stok.catatan',
                'bahan_bakus.nama as nama_bahan',
                'users.name as nama_user',
            ])
            ->orderBy('mutasi_stok.created_at')
            ->get();

        $this->summary = [
            'stok_masuk'          => $this->records->where('jenis_mutasi', 'stok_masuk')->sum('qty'),
            'pemakaian_produksi'  => $this->records->where('jenis_mutasi', 'pemakaian_produksi')->sum('qty'),
            'stok_rusak'          => $this->records->where('jenis_mutasi', 'stok_rusak')->sum('qty'),
            'stok_expired'        => $this->records->where('jenis_mutasi', 'stok_expired')->sum('qty'),
            'penyesuaian'         => $this->records->where('jenis_mutasi', 'penyesuaian')->sum('qty'),
            'total_mutasi'        => $this->records->count(),
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
            new LaporanStokExport($start, $end),
            'laporan-stok-' . now()->format('Ymd_His') . '.xlsx'
        );
    }
}
