# 🧭 BROWSTIME Development Roadmap

Project roadmap dan to-do list untuk pengembangan aplikasi e-commerce **BROWSTIME**  
(Topik skripsi: digitalisasi UMKM make-to-order dengan manajemen stok bahan baku dan evaluasi usability + efisiensi).

---

## 🏗️ Branch Structure

| Branch | Keterangan |
|--------|-------------|
| `dev` | Stable branch – hanya merge release final |
| `<custom-branch>`  | Integration branch – PR aktif & testing |
| feature branches | Dikerjakan per modul (misal `feat/filament-materials`) |

---

## ⚙️ Phase 0 — Audit Database & Existing API
**Branch:** `chore/audit-db-and-api`

### 🎯 Tujuan
Validasi struktur database & endpoint API yang sudah ada sebelum membangun ulang Filament dan frontend.

### ✅ To-Do
- [X] Jalankan `php artisan about` → pastikan Laravel 12 + Filament 4.1 aktif.  
- [X] Import `browstime.sql` → verifikasi semua tabel & relasi.  
- [X] Audit tabel inti:
  - `materials`, `material_stocks`, `products`, `product_recipes`,  
    `orders`, `order_items`, `payments`, `reviews`, `categories`, `users`
- [X] Tambahkan index (jika belum):
  - `material_stocks(material_id, created_at)`
  - `order_items(order_id)`
  - `payments(order_id)`
- [X] Audit endpoint dari `php artisan route:list` (API v1).  
- [X] Tulis hasil audit di `docs/AUDIT_NOTES.md`.

### 📘 Definition of Done
- Database dan route bersih tanpa error.
- Catatan audit tersedia dan disetujui.

---

## 🧩 Phase 1 — Admin Dashboard (Filament CRUD)
**Branch Payung:** `feat/admin-panel`

Setiap modul dibuat di branch turunan, diuji, lalu merge ke `dev`.

### 1A – Materials
**Branch:** `feat/filament-materials`
- [X] Generate resource → `php artisan make:filament-resource Material --generate`
- [X] Form: `name`, `unit`, `min_qty`
- [X] Table: kolom + accessor `current_stock`
- [X] Header Action “Adjust Stock” → insert ke `material_stocks`
- [X] Filter “Below Minimum”
- [X] (Opsional) Widget LowStockTable  
🧱 **DoD:** CRUD jalan & aksi stok bekerja.

### 1B – Products
**Branch:** `feat/filament-products`
- [X] Resource Product + FileUpload gambar (safe saat edit)
- [X] Relasi BOM (`product_recipes`)
- [X] Kolom stok makeable (optional)
🧱 **DoD:** Produk bisa dibuat + gambar + resep bahan.

### 1C – Orders (Read-only)
**Branch:** `feat/filament-orders`
- [X] Tabel & detail order
- [X] Action ubah status (confirm → ship → done)
🧱 **DoD:** Admin bisa melihat & ubah status order.

### 1D – Payments
**Branch:** `feat/filament-payments`
- [X] Resource Payment + mark as paid/manual proof
🧱 **DoD:** Histori pembayaran tampil.

### 1E – Categories
**Branch:** `feat/filament-categories`
- [X] CRUD kategori (name, slug)

### 1F – Reports & Dashboard
**Branch:** `feat/filament-reports-dashboard`
- [ ] Widget: sales today, pending orders, low stock
- [ ] Page laporan penjualan, persediaan, keuangan  
🧱 **DoD:** Dashboard ringkas berfungsi.

---

## 🔁 Phase 2 — Re-Audit & Routing Final
**Branch:** `chore/re-audit-and-routing`

### ✅ To-Do
- [ ] Re-review hasil audit Phase 0.
- [ ] Finalisasi migration/policy bila perlu.
- [ ] Pastikan prefix route admin/api konsisten.

🧱 **DoD:** Seluruh route aktif, tanpa duplikasi atau yatim.

---

## 🛍️ Phase 3 — Storefront (UI Pembeli)
**Branch Payung:** `feat/storefront`

### 3A – Layout & Catalog
**Branch:** `feat/storefront-layout-and-catalog`
- [ ] Blade + Tailwind layout sederhana (putih)
- [ ] List & detail produk

### 3B – Cart & Checkout
**Branch:** `feat/storefront-cart-checkout`
- [ ] Cart (session/local)
- [ ] Checkout form (guest allowed)
- [ ] Estimasi ongkir (RajaOngkir / placeholder)

### 3C – Payment (Midtrans)
**Branch:** `feat/storefront-payments-midtrans`
- [ ] Integrasi Snap/API (QRIS & transfer)
- [ ] Webhook → update `payments` & `orders.status`

### 3D – Order Tracking & Review
**Branch:** `feat/storefront-orders-review`
- [ ] Riwayat order + konfirmasi penerimaan
- [ ] Review produk

🧱 **DoD:** Alur pembelian → pembayaran → review selesai.

---

## 🧹 Phase 4 — Finalization & Docs
**Branch:** `chore/finalize-and-docs`
- [ ] Seeder dummy (materials, products, recipes)
- [ ] `.env.example` + instruksi install README
- [ ] Fix edge case (FileUpload edit, validasi qty)
- [ ] Bersihkan log & cache
- [ ] Update README & LICENSE  

🧱 **DoD:** Clone → `composer install` → `php artisan migrate --seed` → jalan tanpa error.

---

## 🚀 Phase 5 — Release & Hand-Off
**Branch:** `release/v1.0`
- [ ] Merge `dev` → `main`
- [ ] Tag `v1.0.0`
- [ ] Freeze kode → lanjut penulisan skripsi (Bab II–III, SUS evaluation)

---

## ✍️ Commit & PR Guidelines
- Gunakan prefix `feat|fix|chore|docs|refactor`
- PR Checklist:
  - [ ] Uraian tes manual
  - [ ] Screenshot UI (jika ada)
  - [ ] Log bebas error
- Gunakan **Squash & Merge** ke `dev`, lalu batch release ke `main`.

---