# BROWSTIME E-Commerce (Make-to-Order)

Sistem e-commerce untuk UMKM BROWSTIME dengan fitur manajemen stok berbasis bahan baku (BOM), multi-role admin, pembayaran manual & otomatis (Midtrans), serta fitur pelanggan seperti checkout guest, tracking pesanan, dan ulasan.

Dokumentasi ini berisi urutan development, checklist implementasi, dan standar branch untuk mempermudah tracking progress selama 48 hari development.

---

# 📌 DEVELOPMENT ROADMAP (48 DAYS PLAN)

Roadmap ini dibagi ke dalam 4 Phase besar:

1. **Backend Admin (Filament)**
2. **Customer Frontend**
3. **Payment Integration (Midtrans)**
4. **Polishing + UAT + Finalization**

Setiap task memiliki branch khusus agar proses merging & dokumentasi rapi.

---

# 🟩 PHASE 1 — FILAMENT ADMIN PANEL (BACKEND CORE)

> Fokus: Admin, Staf Produksi, Bagian Keuangan  
> Estimasi: Hari 1 – Hari 14

## ## 1.0 — Setup Project

**Branch:** `feat/setup-project`

-   [x] Install Laravel
-   [x] Install Breeze (Auth customer)
-   [x] Install Filament 4.1
-   [x] Install Spatie Permission
-   [x] Create roles: admin, produksi, keuangan, customer
-   [x] Setup routing & layout dasar
-   [x] Setup DB & `.env`

---

## ## 2.0 — Master Data

### **2.1 Produk**

**Branch:** `feat/master-produk`

-   [X] CRUD Produk
-   [X] Upload gambar
-   [X] Relasi ke kategori
-   [X] Harga, deskripsi, estimasi waktu produksi

### **2.2 Kategori**

**Branch:** `feat/master-kategori`

-   [X] CRUD kategori
-   [X] Relasi 1–many ke produk

### **2.3 Bahan Baku**

**Branch:** `feat/master-bahan-baku`

-   [X] CRUD bahan baku
-   [X] Satuan
-   [X] Stok awal & minimum

### **2.4 Satuan**

**Branch:** `feat/master-satuan`

-   [X] CRUD satuan material (gr, ml, pcs)

---

## ## 3.0 — Resep / Bill of Material (BOM)

**Branch:** `feat/master-bom`

-   [X] CRUD Resep
-   [X] Nested form detail bahan
-   [X] Kebutuhan bahan per produk

---

## ## 4.0 — Mutasi Stok

**Branch:** `feat/mutasi-stok`

-   [X] Pemakaian produksi
-   [X] Stok masuk
-   [X] Stok rusak / expired
-   [X] Penyesuaian stok
-   [X] Log mutasi

---

## ## 5.0 — Tarif Ongkir (Database)

**Branch:** `feat/wilayah-pengiriman`

-   [X] CRUD wilayah pengiriman yang didukung (kecamatan/subdistrict)
-   [X] Simpan subdistrict_id RajaOngkir
-   [X] Tidak ada tarif manual (nominal dihitung dengan API)
-   [X] Relasi pesanan → simpan ongkir final dari API
-   [X] (Opsional) tabel cache ongkir untuk hemat limit

---

## 11.5 — Data Wilayah (RajaOngkir Sync)

Branch: feat/rajaongkir-wilayah

- [X] Endpoint sync provinsi, kota, kecamatan dari RajaOngkir
- [X] Cache ke database untuk dropdown
- [X] Validasi wilayah harus dalam daftar wilayah yang didukung

---

## ## 6.0 — Metode Pembayaran

**Branch:** `feat/metode-pembayaran`

-   [X] CRUD rekening
-   [X] QRIS static (jika manual)
-   [X] Konfigurasi Midtrans key (opsional)

---

## ## 7.0 — Manajemen Pesanan (Admin)

**Branch:** `feat/admin-pesanan`

-   [X] Lihat daftar pesanan
-   [X] Ubah status: pending → paid → produksi → dikirim → selesai
-   [X] Upload/review bukti pembayaran
-   [X] Input nomor resi (manual)

---

## ## 8.0 — Staf Produksi (Make-to-Order)

**Branch:** `feat/produksi`

-   [ ] Lihat pesanan siap produksi
-   [ ] Hitung kebutuhan bahan (BOM × qty)
-   [ ] Validasi stok cukup / kurang
-   [ ] Proses produksi
-   [ ] Mark “Siap Dikirim”

---

## ## 9.0 — Laporan & Dashboard

### **9.1 Dashboard**

**Branch:** `feat/dashboard-admin`

-   [ ] Penjualan harian
-   [ ] Stok rendah
-   [ ] Total pesanan
-   [ ] Grafik performa

### **9.2 Laporan**

**Branch:** `feat/laporan`

-   [ ] Laporan penjualan
-   [ ] Laporan stok
-   [ ] Laporan keuangan
-   [ ] Filter tanggal
-   [ ] Export Excel

---

# 🟧 PHASE 2 — CUSTOMER FRONTEND (LIVEWIRE/BLADE)

> Fokus: Katalog, keranjang, checkout, tracking  
> Estimasi: Hari 15 – Hari 26

---

## ## 10.0 — Katalog Produk

**Branch:** `feat/frontend-katalog`

-   [ ] Tampilkan produk
-   [ ] Search
-   [ ] Filter kategori
-   [ ] Detail produk

---

## ## 11.0 — Keranjang

**Branch:** `feat/frontend-cart`

-   [ ] Tambah ke keranjang
-   [ ] Update qty
-   [ ] Hapus item
-   [ ] Hitung subtotal

---

## ## 12.0 — Checkout Customer

**Branch:** `feat/frontend-checkout`

-   [ ] Form alamat (provinsi → kota → kecamatan)
-   [ ] Hitung ongkir → API RajaOngkir /cost
-   [ ] Tampilkan ETA pengiriman
-   [ ] Pilih metode pembayaran
-   [ ] Hitung total
-   [ ] Buat pesanan + simpan ongkir final
---

## ## 13.0 — Checkout Guest

**Branch:** `feat/frontend-guest-checkout`

-   [ ] Form checkout tanpa login
-   [ ] Assign guest sebagai “pelanggan guest”
-   [ ] Kirim email tracking
-   [ ] Instruksi pembayaran

---

## ## 14.0 — Tracking Pesanan

**Branch:** `feat/frontend-tracking`

-   [ ] Tracking via kode booking
-   [ ] Status timeline
-   [ ] Detail pesanan

---

## ## 15.0 — Pembayaran Manual

**Branch:** `feat/frontend-upload-bukti`

-   [ ] Upload bukti bayar
-   [ ] Update status menunggu verifikasi
-   [ ] Notifikasi email opsional

---

## ## 16.0 — Konfirmasi & Ulasan

**Branch:** `feat/frontend-review`

-   [ ] Konfirmasi terima barang
-   [ ] Rating & komentar
-   [ ] Validasi dari detail pesanan

---

# 🟦 PHASE 3 — MIDTRANS INTEGRATION

> Estimasi: Hari 27 – Hari 32

---

## ## 17.0 — Create Transaction

**Branch:** `feat/midtrans-core`

-   [ ] Install Midtrans PHP SDK
-   [ ] Generate Snap/redirect URL
-   [ ] Redirect customer ke Midtrans
-   [ ] Save transaction_id + order_id

---

## ## 18.0 — Webhook Handling

**Branch:** `feat/midtrans-webhook`

-   [ ] Endpoint webhook
-   [ ] Validasi signature_key
-   [ ] Update status pesanan (paid/deny/expire)
-   [ ] Log error webhook

---

## ## 19.0 — Testing Payment Flow

**Branch:** `feat/midtrans-testing`

-   [ ] Test manual vs otomatis
-   [ ] Fix status mismatch
-   [ ] Cross-check laporan

---

# 🟩 PHASE 4 — POLISHING + UAT + FINALIZATION

> Estimasi: Hari 33 – Hari 48

---

## ## 20.0 — Polishing Frontend

**Branch:** `feat/frontend-polish`

-   [ ] Warna, layout, spacing
-   [ ] Mobile responsive
-   [ ] Komponen UI rapi

---

## ## 21.0 — UAT (User Acceptance Testing)

**Branch:** `feat/uat`

-   [ ] Test flow customer
-   [ ] Test flow admin
-   [ ] Test flow produksi
-   [ ] Test flow keuangan
-   [ ] Fix bug

---

## ## 22.0 — Final Deployment (Local/Hosting)

**Branch:** `feat/deploy-final`

-   [ ] Konfigurasi environment
-   [ ] Queue worker
-   [ ] Webhook midtrans di-hosting
-   [ ] Backup DB

---

# ✔ CHECKLIST GLOBAL (TANPA BRANCH)

Gunakan checklist ini untuk track progress harian.

-   [ ] Master Data Selesai
-   [ ] BOM Selesai
-   [ ] Mutasi Stok Selesai
-   [ ] Pesanan Admin Selesai
-   [ ] Produksi Selesai
-   [ ] Dashboard Selesai
-   [ ] Laporan Selesai
-   [ ] Katalog Customer Selesai
-   [ ] Keranjang Selesai
-   [ ] Checkout Customer Selesai
-   [ ] Checkout Guest Selesai
-   [ ] Tracking Selesai
-   [ ] Upload Bukti Selesai
-   [ ] Review Produk Selesai
-   [ ] Midtrans Create Transaction
-   [ ] Midtrans Webhook
-   [ ] UAT Admin
-   [ ] UAT Customer
-   [ ] Final Deploy
-   [ ] Bab 6
-   [ ] Bab 7

---

# 👍 CATATAN PENTING

-   Semua branch dimulai dari `dev`, merge ke `main` hanya ketika modul selesai.
-   Penamaan branch fleksibel tapi **harus konsisten**.
-   Ambil modul per 1–2 hari agar progress stabil.

---

# ✨ Selesai.

README ini siap dipakai untuk GitHub dan dokumentasi development.
