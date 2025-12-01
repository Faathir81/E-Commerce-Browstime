<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPenjualanExport implements FromQuery, WithHeadings, WithMapping
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
        $query = Pembayaran::query()
            ->join('pesanans', 'pembayarans.pesanan_id', '=', 'pesanans.id')
            ->leftJoin('users', 'pesanans.user_id', '=', 'users.id')
            ->where('pembayarans.status', 'valid');

        if ($this->start && $this->end) {
            $query->whereBetween('pembayarans.created_at', [$this->start, $this->end]);
        }

        return $query->select([
            'pembayarans.created_at as tanggal_pembayaran',
            'pesanans.kode as kode_pesanan',
            'users.name as nama_customer',
            'pesanans.guest_email',
            'pembayarans.metode',
            'pesanans.total as total_pesanan',
            'pembayarans.jumlah',
            'pembayarans.status as status_pembayaran',
            'pesanans.status as status_pesanan',
        ])->orderBy('pembayarans.created_at');
    }

    public function map($row): array
    {
        $namaCustomer = $row->nama_customer ?? $row->guest_email ?? 'Guest';

        return [
            Carbon::parse($row->tanggal_pembayaran)->format('d/m/Y H:i'),
            $row->kode_pesanan,
            $namaCustomer,
            $row->metode,
            (float) $row->total_pesanan,
            (float) $row->jumlah,
            $row->status_pembayaran,
            $row->status_pesanan,
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal Pembayaran',
            'Kode Pesanan',
            'Customer',
            'Metode Pembayaran',
            'Total Pesanan',
            'Jumlah Bayar',
            'Status Pembayaran',
            'Status Pesanan',
        ];
    }
}
