# iStore — Sistem Informasi Penjualan iPhone

Tugas proyek kelompok mata kuliah Sistem Informasi. Proyek ini dimulai sebagai prototipe internal untuk owner dan karyawan.

## Menjalankan prototipe

Buka [index.html](index.html) di browser. Tidak perlu instalasi paket. Perubahan data demo disimpan pada localStorage browser. Gunakan **Pengaturan → Reset data demo** untuk memulihkan data awal.

## Fitur yang ada pada prototipe

- Dashboard ringkasan omzet, laba demo, transaksi, stok, grafik, dan metode pembayaran.
- Katalog 40 varian contoh iPhone 14–18 reguler dan Pro Max, kapasitas 256/512 GB, pilihan warna putih/pink.
- Pencarian katalog berdasarkan produk atau IMEI, filter model/kapasitas, serta ubah data produk.
- Form untuk mencatat transaksi penjualan, stok masuk, dan arus kas.
- Laporan omzet, modal barang terjual, biaya operasional, dan laba bersih.
- Tampilan akun owner/karyawan dan ringkasan hak akses.
- Ekspor katalog menjadi CSV.

## Catatan data

Stok, foto ilustratif, deskripsi, transaksi, dan IMEI merupakan data fiktif untuk demo. Harga jual merupakan campuran angka acuan riset dan angka pendekatan untuk varian yang tidak tersedia. Harga modal demo dihitung dengan asumsi 90% harga jual; ini bukan harga distributor/pemasok. Harga modal nyata perlu dicatat dari nota pembelian stok. Warna putih/pink adalah pilihan katalog demo dan tidak menggambarkan opsi resmi untuk setiap model. Jangan gunakan IMEI demo sebagai identitas perangkat sungguhan.

## Status implementasi

Saat ini yang tersedia adalah prototipe frontend HTML, CSS, dan JavaScript. Belum ada autentikasi server, database MySQL, atau pembatasan akses owner/karyawan yang aman. Rencana pengembangan berikutnya adalah memindahkan alur dan tampilan ini ke Laravel, menambahkan MySQL, autentikasi, validasi server, dan role owner/karyawan.
