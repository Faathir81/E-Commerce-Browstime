<?php

namespace App\Exports;

use App\Models\MutasiStok;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanStokExport implements FromQuery, WithHeadings, WithMapping
{
    public ?Carbon $start;
    public ?Carbon $end;

    public function __construct(?Carbon $start = null, ?Carbon $end = null)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public function query()
    {
        $query = MutasiStok::query()
            ->join('bahan_bakus', 'mutasi_stok.bahan_id', '=', 'bahan_bakus.id')
            ->leftJoin('users', 'mutasi_stok.user_id', '=', 'users.id');

        if ($this->start && $this->end) {
            $query->whereBetween('mutasi_stok.created_at', [$this->start, $this->end]);
        }

        return $query->select([
            'mutasi_stok.created_at',
            'bahan_bakus.nama as nama_bahan',
            'mutasi_stok.jenis_mutasi',
            'mutasi_stok.qty',
            'mutasi_stok.stok_awal',
            'mutasi_stok.stok_akhir',
            'users.name as nama_user',
            'mutasi_stok.catatan',
        ])->orderBy('mutasi_stok.created_at');
    }

    public function map($row): array
    {
        return [
            Carbon::parse($row->created_at)->format('d/m/Y H:i'),
            $row->nama_bahan,
            str_replace('_', ' ', ucfirst($row->jenis_mutasi)),
            (float) $row->qty,
            (float) $row->stok_awal,
            (float) $row->stok_akhir,
            $row->nama_user ?? '-',
            $row->catatan ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Bahan Baku',
            'Jenis Mutasi',
            'Qty',
            'Stok Awal',
            'Stok Akhir',
            'User',
            'Catatan',
        ];
    }
}
