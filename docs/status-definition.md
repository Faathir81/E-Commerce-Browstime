# Definisi Status

## Order Status (MVP)
`pending -> awaiting_payment -> paid -> processing -> shipped -> delivered -> cancelled`

- **pending**: order baru dibuat dari checkout, item & harga_frozen tersimpan.
- **awaiting_payment**: (opsional) jika butuh jeda sebelum bayar.
- **paid**: pembayaran terverifikasi → **TRIGGER pengurangan stok bahan baku** lewat penulisan record `material_stocks (type='out')` sesuai resep.
- **processing**: produksi/persiapan.
- **shipped**: dikirim.
- **delivered**: diterima (konfirmasi buyer).
- **cancelled**: batal (stok tidak otomatis kembali, lakukan `adjust` manual jika perlu).

## Payment Status
`pending -> verified / failed`
- **verified**: amount ≥ grand_total (atau kebijakanmu), mengubah Order ke **paid**.
