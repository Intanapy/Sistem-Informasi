# iStore — Prototipe internal

Prototipe antarmuka sistem operasional toko iPhone untuk tugas mata kuliah Sistem Informasi.

## Menjalankan demo

Buka `index.html` di browser. Data demo disimpan di `localStorage` browser yang sedang dipakai. Menu Pengaturan menyediakan tombol untuk mengembalikan data demo ke kondisi awal.

## Fitur antarmuka

- Dashboard omzet, laba, unit terjual, persediaan, grafik, dan metode pembayaran.
- Katalog 40 varian contoh: iPhone 14–18, reguler dan Pro Max, kapasitas 256/512 GB, warna putih/pink.
- Pencarian produk/IMEI, filter model dan kapasitas, ubah harga, modal, stok, dan deskripsi.
- Pencatatan transaksi, stok masuk, serta arus kas pemasukan/pengeluaran.
- Laporan omzet, modal barang terjual, biaya operasional, dan laba bersih.
- Tampilan akun/hak akses owner dan karyawan.
- Ekspor daftar katalog sebagai CSV.
- Data demo bertahan setelah halaman ditutup selama localStorage browser tidak dihapus.

## Catatan data

Semua stok, transaksi, gambar ilustratif, deskripsi, dan IMEI merupakan data fiktif untuk presentasi. Harga jual benih memakai angka acuan yang ditemukan saat riset dan angka pendekatan untuk varian yang tidak tersedia. Harga modal awal adalah simulasi 90% dari harga jual, bukan harga pemasok. Saat sistem sebenarnya dipakai, harga modal dimasukkan dari nota penerimaan stok.

Pilihan warna putih/pink diterapkan untuk konsistensi dataset demo; kombinasi tersebut tidak mewakili pilihan warna resmi semua generasi iPhone. IMEI demo bersifat placeholder dan bukan identitas perangkat sungguhan.

## Menjalankan versi backend

Untuk memakai data produk dan stok yang tersimpan ke database, jalankan Laravel pada folder `backend`, lalu buka `http://127.0.0.1:8000`. Setelah login, halaman aplikasi terpadu tersedia di `/app`.

Katalog, stok masuk, transaksi, arus kas, dan laporan tersambung ke API Laravel pada versi backend. Penjualan lunas mengurangi stok dan mencatat pemasukan; transaksi yang menunggu pembayaran dapat dikonfirmasi kemudian. Buka [backend/README.md](backend/README.md) untuk langkah setup dan akun demo.

