<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanKeuanganExport implements FromQuery, WithHeadings, WithMapping
{
    public ?Carbon $start;
    public ?Carbon $end;
    public string $groupBy;

    public function __construct(?Carbon $start = null, ?Carbon $end = null, string $groupBy = 'daily')
    {
        $this->start   = $start;
        $this->end     = $end;
        $this->groupBy = $groupBy;
    }

    public function query()
    {
        $baseQuery = Pembayaran::query()
            ->where('status', 'valid');

        if ($this->start && $this->end) {
            $baseQuery->whereBetween('created_at', [$this->start, $this->end]);
        }

        if ($this->groupBy === 'monthly') {
            $query = $baseQuery
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as periode")
                ->selectRaw('SUM(jumlah) as total_revenue')
                ->selectRaw('COUNT(*) as jumlah_transaksi')
                ->groupBy('periode')
                ->orderBy('periode');
        } else {
            $query = $baseQuery
                ->selectRaw('DATE(created_at) as periode')
                ->selectRaw('SUM(jumlah) as total_revenue')
                ->selectRaw('COUNT(*) as jumlah_transaksi')
                ->groupBy('periode')
                ->orderBy('periode');
        }

        return $query;
    }

    public function map($row): array
    {
        if ($this->groupBy === 'monthly') {
            $periodeLabel = Carbon::createFromFormat('Y-m', $row->periode)->format('m/Y');
        } else {
            $periodeLabel = Carbon::parse($row->periode)->format('d/m/Y');
        }

        $avg = $row->jumlah_transaksi > 0
            ? $row->total_revenue / $row->jumlah_transaksi
            : 0;

        return [
            $periodeLabel,
            (float) $row->total_revenue,
            (int) $row->jumlah_transaksi,
            (float) $avg,
        ];
    }

    public function headings(): array
    {
        return [
            $this->groupBy === 'monthly' ? 'Bulan' : 'Tanggal',
            'Total Pendapatan',
            'Jumlah Transaksi',
            'Rata-rata per Transaksi',
        ];
    }
}
