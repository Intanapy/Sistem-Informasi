# Backend Laravel iStore

Backend ini memakai Laravel 13 dan MySQL/MariaDB. Halaman aplikasi terpadu tersedia di `/app`; halaman produk dan stok masuk membaca serta menyimpan data melalui API Laravel. Transaksi, arus kas, laporan, dan beberapa angka dashboard masih berupa data demo browser.

## Kebutuhan

- PHP 8.3 atau lebih baru beserta ekstensi OpenSSL, PDO MySQL, Mbstring, Fileinfo, Tokenizer, XML, Ctype, dan cURL.
- Composer 2.
- MySQL 8 atau kompatibel.

## Menyiapkan aplikasi

Jalankan dari folder `backend`:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
```

Buat database bernama `istore`, sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` pada `.env`, lalu jalankan:

```powershell
php artisan migrate --seed
php artisan serve
```

Buka `http://127.0.0.1:8000`. Setelah login, aplikasi terpadu terbuka di `/app`. Seeder membuat akun demo:

- Owner: `owner@istore.demo` / `password`
- Karyawan: `staff@istore.demo` / `password`

Ganti kata sandi demo sebelum sistem digunakan di luar presentasi.

## Peran

- Owner dapat mengelola produk/harga, melihat laporan, dan membuat akun karyawan.
- Karyawan dapat melihat produk, mencatat penjualan, stok masuk, serta arus kas.
- Semua halaman dan endpoint JSON internal memerlukan sesi login. Pendaftaran publik tidak disediakan.

## Endpoint utama

Semua endpoint menggunakan session login Laravel. Request POST/PATCH dari browser perlu mengirim token CSRF, misalnya melalui header `X-CSRF-TOKEN` dari meta tag halaman.

| Metode | Endpoint | Hak akses |
| --- | --- | --- |
| GET | `/api/dashboard` | Owner dan karyawan |
| GET | `/api/products` | Owner dan karyawan |
| POST | `/api/products` | Owner |
| PATCH | `/api/products/{variant}` | Owner |
| GET, POST | `/api/sales` | Owner dan karyawan |
| POST | `/api/sales/{sale}/confirm-payment` | Pemilik transaksi atau owner |
| GET, POST | `/api/stock-entries` | Owner dan karyawan |
| GET, POST | `/api/cash-flows` | Owner dan karyawan |
| GET | `/api/reports` | Owner |
| GET, POST | `/api/employees` | Owner |

## Data dan perhitungan

Migrasi menyimpan produk, varian, unit stok individual, IMEI unik, transaksi, penerimaan stok, dan arus kas. IMEI seed adalah angka fiktif. Saat stok dicatat, masukkan satu IMEI berbeda untuk setiap unit.

Transaksi `pending` belum mengurangi stok atau menambah omzet. Saat pembayaran dikonfirmasi, backend mengunci dan mengambil unit stok, mencatat harga modal rata-rata tertimbang, lalu membuat pemasukan arus kas satu kali. Pembelian stok adalah pengeluaran kas tetapi tidak langsung mengurangi laba; HPP mengurangi laba ketika unit terjual.

## Catatan harga

Harga jual seed merupakan data simulasi untuk presentasi, bukan daftar harga resmi yang dijamin berlaku. Harga modal seed menggunakan pendekatan 90% dari harga jual; ganti dengan data nota pemasok untuk perhitungan nyata. Foto produk belum diunggah; field `photo_path` disediakan agar dapat ditambahkan kemudian.

## Batasan saat ini

Katalog dan stok masuk sudah menggunakan database. Form stok meminta satu IMEI 15 digit unik untuk setiap unit. Perubahan harga jual dan pembuatan varian dibatasi untuk owner. Fitur transaksi, arus kas, laporan, dan beberapa angka dashboard masih memakai data demo browser; fitur-fitur tersebut akan disambungkan pada tahap berikutnya.

