# Use Case Details

## UC1 — Browse Products
**Aktor**: Buyer  
**Pre**: Produk tersedia.  
**Main**: Lihat daftar, filter kategori, lihat detail (gambar, harga, makeable_qty).  
**Post**: —  
**Alt**: Produk kosong → tampilkan empty state.

## UC2 — Add to Cart
**Aktor**: Buyer  
**Pre**: Produk aktif.  
**Main**: Tambah ke cart; sistem validasi kuantitas ≤ makeable_qty.  
**Post**: Cart tersimpan (session / user).  
**Alt**: Melebihi makeable_qty → tampilkan kuota maksimal.

## UC3 — Checkout
**Aktor**: Buyer (guest/login)  
**Pre**: Cart valid.  
**Main**:
1. Isi data pengiriman (guest: form; login: pilih alamat).
2. Sistem hitung subtotal + ongkir sederhana.
3. Sistem buat **Order (status: pending)** + **OrderItems (harga_frozen)**.
4. Tampilkan instruksi pembayaran (VA/transfer/QRIS manual).
**Post**: Order tercatat; stok belum berkurang.  
**Alt**: Makeable_qty berubah → minta konfirmasi qty baru.

## UC4 — Pay Order / Upload Proof
**Aktor**: Buyer / Admin  
**Pre**: Order pending/awaiting_payment.  
**Main**:
1. Buyer upload bukti / Admin input metode dan jumlah.
2. Admin verifikasi → **Payment.status = verified**, **Order.status = paid**.
3. Sistem kurangi stok bahan baku berdasar resep.
**Post**: Stok bahan berkurang; order siap diproses/produksi.  
**Alt**: Payment gagal → status failed.

## UC5 — View My Orders
**Aktor**: Buyer  
**Pre**: Order ada.  
**Main**: Lihat list & detail status timeline.  
**Post**: —  

## UC6 — Confirm Received
**Aktor**: Buyer  
**Pre**: Order shipped/delivered.  
**Main**: Buyer konfirmasi diterima → status delivered.  
**Post**: Order selesai.  

## UC7 — Write Review (opsional)
**Aktor**: Buyer  
**Pre**: Order delivered.  
**Main**: Isi rating & komentar.  
**Post**: Review tersimpan.  

## UC8 — Manage Products/Categories/Images
**Aktor**: Admin  
**Main**: CRUD produk/kategori, upload cover & galeri.

## UC9 — Manage Materials & Recipes
**Aktor**: Admin  
**Main**: CRUD bahan baku, mutasi stok (in/out/adjust), set resep (qty_per_unit).

## UC10 — Record/Verify Payment
**Aktor**: Admin  
**Main**: Input/validasi pembayaran; ubah order ke **paid**; trigger pengurangan stok.

## UC11 — View Reports
**Aktor**: Admin  
**Main**: Laporan harian penjualan & ringkas stok bahan.
