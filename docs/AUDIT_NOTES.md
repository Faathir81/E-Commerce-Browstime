# 🧩 Codebase Audit Report — Phase 0 (Existing Code)

**Branch:** `chore/audit-db-and-api`  
**Tanggal Audit:** 28 Oktober 2025  
**Auditor:** Faathir Akbar Nugroho  
**Tujuan:** Memetakan struktur existing codebase, mengevaluasi kesesuaian arsitektur dengan API v1, serta mengidentifikasi gap fitur sebelum migrasi ke Filament Admin.

---

## 🎯 Tujuan Audit
- Memastikan arsitektur source code modular dan siap dikembangkan ke Filament Admin.  
- Memeriksa konsistensi antar layer (Controller → Request → Service → Model).  
- Mengidentifikasi file yang belum terhubung ke endpoint API aktif.  
- Menandai fitur yang belum diimplementasikan (terutama Review & Product Detail API).

---

## 🗃️ 1. Struktur Arsitektur

| Layer | Lokasi | Fungsi |
|--------|--------|--------|
| **Controllers** | `app/Http/Controllers/...` | Mengatur alur request–response tiap domain |
| **Requests** | `app/Http/Requests/...` | Validasi input dari user sebelum diteruskan ke service |
| **Services** | `app/Services/...` | Menyimpan business logic utama |
| **Models** | `app/Models/...` | ORM (Eloquent) untuk interaksi database |
| **Events & Listeners** | `app/Events`, `app/Listeners` | Mengatur trigger & reaksi otomatis (OrderPlaced, PaymentVerified) |
| **Policies** | `app/Policies/...` | Otentikasi akses (untuk Filament / role admin) |
| **Support** | `app/Support/...` | Helper generik (`ApiResponse`, `PaginationHelper`) |
| **View Components** | `app/View/Components/...` | Komponen Blade layout (untuk halaman non-admin) |

✅ Struktur sudah **clean, modular, dan scalable** — cocok untuk integrasi Filament.

---

## 🧠 2. Domain-Level Mapping

### 🔐 Auth
**Controllers:**  
`AuthenticatedSessionController`, `RegisteredUserController`, `PasswordController`, `NewPasswordController`, `VerifyEmailController`, dll.  
**Requests:**  
`LoginRequest`  
✅ Lengkap untuk login, register, reset, dan verifikasi email.  
⚠️ Pastikan `logout` route tersedia (biasanya di `AuthenticatedSessionController@destroy`).

---

### 🏪 Catalog
**Controllers:**  
`CategoryController`, `ProductController`, `ProductImageController`  
**Requests:**  
`ProductIndexRequest`, `ProductImageStoreRequest`  
**Services:**  
`CategoryService`, `ProductService`, `ProductImageService`  
**Models:**  
`Category`, `Product`, `ProductImage`, `ProductRecipe`

✅ CRUD sudah lengkap dan service-oriented.  
⚠️ *Missing:* `show()` endpoint detail produk tunggal (`GET /api/v1/products/{id}`).  
⚠️ *Missing:* Integrasi dengan model `Review`.

---

### 🛒 Order
**Controllers:**  
`CartController`, `CheckoutController`, `OrderController`  
**Requests:**  
`CartAddRequest`, `CartUpdateRequest`, `CheckoutRequest`  
**Services:**  
`CartService`, `OrderService`  
**Events:**  
`OrderPlaced`, `OrderPaid`  
**Listeners:**  
`ReduceStockOnOrderPaid`, `SendOrderStatusNotification`  
**Models:**  
`Order`, `OrderItem`

✅ Flow order lengkap (add cart → checkout → place order → reduce stock).  
⚠️ *Minor:* Pastikan `ReduceStockOnOrderPaid` juga sinkron dengan tabel `material_stocks`.

---

### 💳 Payment
**Controllers:**  
`PaymentController`, `MidtransWebhookController`  
**Requests:**  
`PaymentCreateRequest`, `PaymentProofUploadRequest`  
**Services:**  
`PaymentService`, `MidtransService`  
**Events:**  
`PaymentVerified`  
**Model:**  
`Payment`

✅ Lengkap untuk pembayaran manual dan otomatis (Midtrans).  
⚠️ *Enhancement:* Tambahkan signature verification (HMAC) pada webhook di Phase 1.

---

### 🚚 Shipping
**Controller:** `ShippingController`  
**Service:** `ShippingService`  
✅ Sudah modular.  
Cocok untuk integrasi RajaOngkir / Binderbyte.  
⚠️ *Future:* bisa dikembangkan ke Filament “Shipping Settings”.

---

### 📊 Report
**Controller:** `ReportController`  
**Service:** `ReportService`  
✅ Sudah ada endpoint laporan & dashboard summary.  
Cocok untuk Filament widget “RevenueToday”, “LowStock”, dll.

---

### 🧾 Stock
**Services:** `StockService`, `RecipeService`  
**Models:** `Material`, `MaterialStock`, `ProductRecipe`  
✅ Core make-to-order logic sudah siap.  
⚠️ Belum ada Controller publik (akan dihandle lewat Filament Admin).

---

### 💬 Review
**Model:** `Review`  
❌ Belum ada Controller, Request, atau Service.  
📌 Akan dibuat baru di Phase 1.

---

## 🧱 3. Summary Kekurangan

| Domain | Kekurangan | Solusi (Phase 1) |
|----------|-------------|----------------|
| **Catalog** | Belum ada detail produk endpoint | Tambah `show()` di `ProductController` |
| **Review** | Belum ada modul sama sekali | Tambah `ReviewController`, `ReviewService`, `ReviewRequest` |
| **Stock** | Tidak ada API publik | Implementasi di Filament Admin |
| **Security** | Webhook belum divalidasi signature | Tambah HMAC signature verification |
| **Event Sync** | Listener stock perlu cek sinkronisasi `material_stocks` | Review & refactor stock sync |

---

## 🚀 4. Rencana Lanjutan (Phase 1–2)

| Phase | Fokus | Branch | Status |
|--------|--------|--------|--------|
| **Phase 0** | Audit Database, API, dan Existing Code | `chore/audit-db-and-api` | ✅ Done |
| **Phase 1** | Tambah modul Review + detail produk + setup Filament | `feat/filament-admin` | 🔜 Next |
| **Phase 2** | Integrasi frontend Livewire (catalog, checkout, order) | `feat/frontend-livewire` | ⏳ Upcoming |

---

## ✅ 5. Kesimpulan
Codebase BROWSTIME sudah **siap lanjut ke tahap implementasi Filament Admin (Phase 1)**.  
Struktur existing code menunjukkan fondasi kuat dengan arsitektur modular dan penggunaan Service Layer yang matang.  
Kekurangan utama hanya minor dan bersifat ekspansi fitur, bukan bug struktural.

---

**Disusun oleh:**  
🧑‍💻 *Faathir Akbar Nugroho*  
_Developer & Peneliti — E-Commerce BROWSTIME_
