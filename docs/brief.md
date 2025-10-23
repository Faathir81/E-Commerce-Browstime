# Product Brief — BROWSTIME (MVP 1 Bulan)

## Context & Problem
BROWSTIME (UMKM cookies, make-to-order) menghadapi: pesanan tersebar multi-channel, stok bahan baku dicatat manual, laporan penjualan belum rapi, dan keteteran saat peak season.

## Target Users
- **Guest/Buyer (guest atau login)**: browsing katalog, add to cart, checkout, bayar manual/QRIS, lihat status pesanan, (opsional) review.
- **Admin**: kelola produk/kategori/gambar, kelola bahan baku & resep (konversi bahan → produk), kelola pesanan, input/validasi pembayaran, lihat laporan sederhana.

## Goal
Merilis web e-commerce sederhana end-to-end dalam 1 bulan dengan stok berbasis **bahan baku** dan pembayaran manual/QRIS, cukup untuk operasional harian dan bahan skripsi.

## Non-Goal
- Integrasi kurir realtime / API ongkir.
- Multi-gudang & multi-warehouse.
- Loyalty/kupon/point & promosi kompleks.
- Integrasi payment gateway penuh (boleh opsional, cukup instruksi manual/QRIS).

## MVP Scope (1 Bulan)
Katalog → Cart → Checkout → Pembayaran manual/QRIS → Pengurangan stok bahan baku saat **paid/picked** → Laporan sederhana → Panel admin.

## Evaluation
- **Usability**: SUS (System Usability Scale).
- **Efficiency**: (1) waktu proses order (checkout→bayar→verified), (2) akurasi stok (selisih sistem vs fisik sampling), (3) lead time produksi (opsional).

## Scope Cut
Tanpa API ongkir (pakai tabel tarif sederhana), tanpa kurir realtime, tanpa multi-gudang.

## Acceptance Checklist (MVP)
- [ ] Guest bisa checkout & menerima instruksi bayar (transfer/QRIS).
- [ ] Harga tiap item di-**freeze** saat checkout.
- [ ] Order mengurangi stok **bahan baku** saat status **paid** (atau **picked**, sesuai implementasi).
- [ ] Admin bisa input stok in/out/adjust + mengelola resep per produk.
- [ ] **Makeable qty** produk tampil (berdasar bahan baku saat ini).
- [ ] Laporan harian penjualan dan ringkas stok tersedia di panel admin.
