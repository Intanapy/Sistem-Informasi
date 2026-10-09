# iStore — Sistem Informasi Penjualan iPhone

Proyek kelompok mata kuliah Sistem Informasi. Repositori ini berisi prototipe frontend dan backend Laravel untuk sistem internal toko iPhone dengan peran owner dan karyawan.

## Struktur proyek

- **Frontend demo:** `index.html`, `style.css`, dan `app.js`. Buka `index.html` langsung di browser; perubahan demo tersimpan di localStorage.
- **Backend Laravel 13 + MySQL:** folder [backend](backend/README.md). Backend memiliki login berbasis session, API internal, pengelolaan peran, migrasi MySQL, seeder, stok unit dengan IMEI unik, penjualan, arus kas, dan laporan laba.

## Fitur prototipe frontend

- Dashboard omzet, laba demo, transaksi, stok, grafik, dan metode pembayaran.
- Katalog 40 varian contoh iPhone 14–18 reguler dan Pro Max, kapasitas 256/512 GB, warna putih/pink.
- Pencarian berdasarkan nama/IMEI, filter model dan kapasitas, serta form transaksi, stok masuk, dan arus kas.
- Laporan omzet, modal barang terjual, biaya operasional, dan laba bersih.
- Ekspor katalog menjadi CSV.

## Catatan data demo

Stok, foto ilustratif, deskripsi, transaksi, dan IMEI adalah fiktif. Harga jual merupakan acuan demo, sementara modal memakai asumsi 90% dari harga jual; angka tersebut bukan harga pemasok. Warna putih/pink tidak mewakili warna resmi semua model. Jangan gunakan IMEI demo untuk perangkat sungguhan.

## Status

Backend sudah disiapkan di `backend/`, tetapi prototipe frontend di root belum dihubungkan ke endpoint backend. Ikuti panduan di [backend/README.md](backend/README.md) untuk memasang dependensi dan menyiapkan MySQL. Instalasi dependensi dan migrasi belum dijalankan di lingkungan pembuatan karena PHP di lingkungan tersebut tidak menyediakan OpenSSL.
