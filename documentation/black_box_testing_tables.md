# Hasil Black Box Testing - BROWSTIME E-Commerce

Dokumen ini berisi hasil pengujian black box untuk semua fitur utama aplikasi BROWSTIME E-Commerce.

---

## Tabel 1. Hasil Black Box Testing Login Multi-Role

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Melakukan login sebagai Admin | Memasukkan email `admin@browstime.com` dan password yang benar, kemudian klik tombol Login | Sistem berhasil melakukan autentikasi dan mengarahkan ke dashboard Admin dengan akses penuh ke manajemen pesanan, produk, dan verifikasi pembayaran | Sesuai. Sistem berhasil login dan menampilkan dashboard Admin dengan menu lengkap |
| 2. | Melakukan login sebagai Keuangan | Memasukkan email `keuangan@browstime.com` dan password yang benar, kemudian klik tombol Login | Sistem berhasil melakukan autentikasi dan mengarahkan ke dashboard Keuangan dengan akses ke laporan penjualan dan stok bahan baku | Sesuai. Sistem berhasil login dan menampilkan dashboard Keuangan dengan menu laporan |
| 3. | Melakukan login sebagai Produksi | Memasukkan email `produksi@browstime.com` dan password yang benar, kemudian klik tombol Login | Sistem berhasil melakukan autentikasi dan mengarahkan ke dashboard Produksi dengan akses ke manajemen produksi | Sesuai. Sistem berhasil login dan menampilkan dashboard Produksi |
| 4. | Melakukan login sebagai Pelanggan | Memasukkan email `pelanggan@browstime.com` dan password yang benar, kemudian klik tombol Login | Sistem berhasil melakukan autentikasi dan mengarahkan ke halaman utama dengan akses untuk berbelanja dan melihat pesanan | Sesuai. Sistem berhasil login dan menampilkan halaman utama dengan menu pelanggan |

---

## Tabel 2. Hasil Black Box Testing Registrasi Pelanggan

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mendaftar akun pelanggan baru | Mengisi form registrasi dengan nama "Test Customer", email "testcustomer@test.com", password "password123", konfirmasi password "password123", kemudian klik tombol Sign Up | Sistem berhasil membuat akun baru, melakukan auto-login, dan mengarahkan pengguna ke halaman utama | Sesuai. Akun berhasil dibuat, sistem otomatis login dan redirect ke home page |
| 2. | Verifikasi data akun tersimpan | Setelah registrasi berhasil, klik menu user profile untuk melihat informasi akun | Sistem menampilkan email pengguna yang baru terdaftar (testcustomer@test.com) di menu profile | Sesuai. Email pengguna ditampilkan dengan benar di user menu |
| 3. | Verifikasi session login otomatis | Setelah registrasi, periksa status login pengguna | Pengguna otomatis dalam keadaan login tanpa perlu memasukkan kredensial lagi | Sesuai. Pengguna langsung dalam status logged in setelah registrasi |

---

## Tabel 3. Hasil Black Box Testing Pemesanan Produk (Tambah ke Keranjang)

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Menambahkan produk ke keranjang | Login sebagai pelanggan, browse katalog produk, pilih "Classic Dark Chocolate – Pouch (90 grams)", klik tombol "Add to Cart" | Produk berhasil ditambahkan ke keranjang dengan harga Rp 90.000, jumlah item di icon keranjang bertambah | Sesuai. Produk berhasil masuk keranjang dengan harga yang benar |
| 2. | Melihat isi keranjang | Klik icon keranjang di header untuk melihat daftar produk | Sistem menampilkan halaman keranjang dengan produk yang telah ditambahkan, termasuk nama, harga, dan total | Sesuai. Halaman keranjang menampilkan semua informasi produk dengan lengkap |
| 3. | Verifikasi perhitungan total | Periksa total harga di halaman keranjang | Total harga dihitung dengan benar sesuai jumlah dan harga produk | Sesuai. Perhitungan total harga akurat |

---

## Tabel 4. Hasil Black Box Testing Checkout sebagai Tamu (Guest)

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Melakukan checkout tanpa login | Logout dari akun, tambahkan produk ke keranjang, klik "Proceed to Checkout" | Sistem menampilkan pesan "Checking out as guest" dan form untuk mengisi data tamu (nama, email, telepon) | Sesuai. Sistem mendeteksi guest user dan menampilkan form informasi tamu |
| 2. | Mengisi informasi tamu | Isi form dengan nama "Guest User", email "guest@example.com", telepon "08123456789", alamat lengkap, pilih provinsi, kota, dan kecamatan | Semua data tamu terisi dengan benar dan dapat melanjutkan ke tahap pengiriman | Sesuai. Form berhasil diisi dan validasi berjalan dengan baik |
| 3. | Menyelesaikan pesanan sebagai tamu | Pilih metode pengiriman dan pembayaran, klik "Place Order" | Sistem berhasil membuat pesanan dengan Order ID (ORD-U0O3R5), menampilkan halaman konfirmasi, dan mengirim notifikasi ke email tamu | Sesuai. Pesanan berhasil dibuat dengan Order ID ORD-U0O3R5, konfirmasi ditampilkan |

---

## Tabel 5. Hasil Black Box Testing Checkout dan Pengiriman

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mengisi alamat pengiriman | Login sebagai pelanggan, lanjutkan ke checkout, isi alamat pengiriman dengan provinsi "Banten", kota "Tangerang", kecamatan, alamat lengkap, dan kode pos | Sistem menyimpan alamat pengiriman dan menampilkan opsi metode pengiriman | Sesuai. Alamat tersimpan dan form pengiriman muncul |
| 2. | Memilih metode pengiriman | Pilih metode pengiriman yang tersedia berdasarkan lokasi tujuan | Sistem menghitung biaya pengiriman secara otomatis berdasarkan lokasi dan menampilkan total biaya (produk + ongkir) | Sesuai. Biaya pengiriman dihitung otomatis dan total diperbarui dengan benar |
| 3. | Menyelesaikan proses checkout | Lanjutkan ke pembayaran dan klik "Place Order" | Sistem berhasil membuat pesanan dengan Order ID (ORD-JCXQQ3) dan menampilkan halaman konfirmasi pesanan | Sesuai. Pesanan berhasil dibuat dengan Order ID ORD-JCXQQ3 |

---

## Tabel 6. Hasil Black Box Testing Pembayaran Pesanan

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Memilih metode pembayaran | Pada halaman pembayaran, pilih metode "QRIS Static" dari daftar metode pembayaran yang tersedia | Sistem menampilkan instruksi pembayaran QRIS dan form untuk upload bukti pembayaran | Sesuai. Instruksi QRIS ditampilkan dengan field upload bukti |
| 2. | Mengupload bukti pembayaran | Pilih file gambar bukti pembayaran dan upload melalui form yang tersedia | File berhasil diupload, sistem menampilkan preview atau konfirmasi file terupload | Sesuai. File bukti pembayaran berhasil diupload |
| 3. | Konfirmasi pembayaran | Klik tombol "Confirm Payment" setelah upload bukti | Sistem menyimpan bukti pembayaran, mengubah status pesanan menjadi "Menunggu Verifikasi", dan menampilkan pesan konfirmasi | Sesuai. Pembayaran tersimpan, status pesanan diupdate, notifikasi ditampilkan |

---

## Tabel 7. Hasil Black Box Testing Verifikasi Pembayaran (Admin)

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Melihat daftar pembayaran pending | Login sebagai admin, akses menu "Payment Verification" atau "Verifikasi Pembayaran" | Sistem menampilkan daftar pesanan yang menunggu verifikasi pembayaran dengan informasi Order ID, nama pelanggan, dan total | Sesuai. Daftar pembayaran pending ditampilkan dengan lengkap |
| 2. | Melihat detail bukti pembayaran | Klik pada salah satu pesanan (ORD-JCXQQ3) untuk melihat detail dan bukti pembayaran | Sistem menampilkan detail pesanan lengkap termasuk gambar bukti pembayaran yang diupload pelanggan | Sesuai. Detail pesanan dan bukti pembayaran dapat dilihat dengan jelas |
| 3. | Menyetujui pembayaran | Klik tombol "Approve" atau "Setujui" untuk memverifikasi pembayaran | Sistem mengubah status pesanan menjadi "Paid" atau "Lunas", menghilangkan pesanan dari daftar pending, dan mengirim notifikasi ke pelanggan | Sesuai. Status pesanan berubah menjadi Paid, pesanan keluar dari daftar pending |

---

## Tabel 8. Hasil Black Box Testing Laporan Penjualan (Keuangan)

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mengakses laporan penjualan | Login sebagai keuangan, klik menu "Laporan Penjualan" atau "Sales Report" | Sistem menampilkan dashboard laporan penjualan dengan data total penjualan, jumlah pesanan, dan statistik penjualan | Sesuai. Dashboard laporan penjualan ditampilkan dengan data lengkap |
| 2. | Melihat detail transaksi penjualan | Periksa tabel atau daftar transaksi penjualan yang tercatat | Sistem menampilkan daftar transaksi dengan informasi Order ID, tanggal, pelanggan, produk, jumlah, dan total penjualan | Sesuai. Daftar transaksi ditampilkan dengan informasi detail yang akurat |
| 3. | Verifikasi perhitungan total penjualan | Periksa total revenue atau pendapatan yang ditampilkan di dashboard | Total penjualan dihitung dengan benar berdasarkan semua transaksi yang telah diverifikasi (status Paid) | Sesuai. Perhitungan total penjualan akurat dan sesuai dengan data transaksi |

---

## Tabel 9. Hasil Black Box Testing Laporan Stok Bahan Baku (Keuangan)

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mengakses laporan stok bahan baku | Login sebagai keuangan, klik menu "Laporan Stok Bahan Baku" atau "Raw Material Stock Report" | Sistem menampilkan halaman laporan stok dengan daftar bahan baku yang tersedia | Sesuai. Halaman laporan stok bahan baku ditampilkan |
| 2. | Melihat mutasi stok bahan baku | Periksa tabel mutasi stok yang menampilkan penggunaan bahan baku untuk produksi | Sistem menampilkan data mutasi stok dengan informasi nama bahan baku, jumlah digunakan, Order ID produksi, dan sisa stok | Sesuai. Tabel mutasi stok menampilkan penggunaan bahan baku dengan detail lengkap |
| 3. | Verifikasi perhitungan sisa stok | Periksa kolom sisa stok setelah penggunaan bahan baku | Sisa stok dihitung dengan benar: Stok Awal - Jumlah Digunakan = Sisa Stok, dan perhitungan akurat untuk setiap transaksi produksi | Sesuai. Perhitungan sisa stok akurat dan konsisten dengan data penggunaan |

---

## Ringkasan Hasil Testing

| No. | Fitur yang Diuji | Jumlah Test Case | Status |
|-----|-----------------|------------------|--------|
| 1. | Login Multi-Role | 4 | ✅ Semua Passed |
| 2. | Registrasi Pelanggan | 3 | ✅ Semua Passed |
| 3. | Pemesanan Produk | 3 | ✅ Semua Passed |
| 4. | Checkout sebagai Tamu | 3 | ✅ Semua Passed |
| 5. | Checkout dan Pengiriman | 3 | ✅ Semua Passed |
| 6. | Pembayaran Pesanan | 3 | ✅ Semua Passed |
| 7. | Verifikasi Pembayaran (Admin) | 3 | ✅ Semua Passed |
| 8. | Laporan Penjualan (Keuangan) | 3 | ✅ Semua Passed |
| 9. | Laporan Stok Bahan Baku (Keuangan) | 3 | ✅ Semua Passed |
| **TOTAL** | **9 Fitur** | **28 Test Case** | **✅ 100% Passed** |

---

## Kesimpulan

Berdasarkan hasil pengujian black box yang telah dilakukan terhadap 9 fitur utama aplikasi BROWSTIME E-Commerce dengan total 28 test case, dapat disimpulkan bahwa:

1. **Semua fitur berfungsi dengan baik** - Tidak ditemukan bug atau error kritis
2. **Alur pengguna (user flow) berjalan lancar** - Dari registrasi, pemesanan, pembayaran, hingga verifikasi
3. **Role-based access control berfungsi dengan benar** - Setiap role (Admin, Keuangan, Produksi, Pelanggan) memiliki akses sesuai kewenangannya
4. **Perhitungan sistem akurat** - Total harga, ongkir, dan stok bahan baku dihitung dengan benar
5. **Fitur guest checkout tersedia dan berfungsi** - Memudahkan pelanggan untuk berbelanja tanpa registrasi

**Status Akhir:** ✅ **Aplikasi siap untuk digunakan (Production Ready)**
