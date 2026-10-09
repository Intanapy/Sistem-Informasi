<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard — iStore</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f7f7fb; color: #29283d; font: 14px Arial, sans-serif; }
        header { height: 64px; display: flex; align-items: center; justify-content: space-between; padding: 0 6vw; background: white; border-bottom: 1px solid #ecebf2; }
        header b { font-size: 20px; letter-spacing: -1px; } header b span { color: #7564e5; }
        .user { display: flex; align-items: center; gap: 16px; color: #77768a; font-size: 12px; }
        .user form { margin: 0; } button { border: 0; border-radius: 6px; padding: 9px 12px; background: #7564e5; color: white; font-weight: 700; cursor: pointer; }
        main { max-width: 1000px; margin: 42px auto; padding: 0 20px; }
        h1 { margin: 0; font-size: 26px; } .subtitle { margin: 8px 0 24px; color: #89889a; }
        .role { display: inline-block; padding: 5px 8px; border-radius: 20px; background: #efedff; color: #6958d7; font-size: 10px; font-weight: 700; text-transform: uppercase; }
        .cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
        .card { min-height: 108px; padding: 18px; border: 1px solid #efeff4; border-radius: 10px; background: white; }
        .card span { color: #89889a; font-size: 11px; } .card strong { display: block; margin-top: 14px; font-size: 21px; }
        .panel { margin-top: 18px; padding: 20px; border: 1px solid #efeff4; border-radius: 10px; background: white; }
        .panel h2 { margin: 0 0 9px; font-size: 15px; } .panel p { color: #77768a; font-size: 12px; line-height: 1.6; }
        .api-list { display: grid; grid-template-columns: repeat(2, 1fr); gap: 9px; margin-top: 14px; }
        .api-list a { padding: 12px; border: 1px solid #efeff4; border-radius: 7px; color: #6555d0; font-size: 12px; text-decoration: none; }
        .message { color: #9998a8; font-size: 12px; }
        @media(max-width:700px) { .cards { grid-template-columns: repeat(2, 1fr); } header { padding: 0 18px; } .user span { display: none; } }
    </style>
</head>
<body>
    <header>
        <b>iStore<span>.</span></b>
        <div class="user"><span>{{ auth()->user()->name }} · {{ auth()->user()->role }}</span>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Keluar</button></form>
        </div>
    </header>
    <main>
        <span class="role">{{ auth()->user()->role }}</span>
        <h1>Dashboard operasional</h1>
        <p class="subtitle">Ringkasan hari ini di iStore.</p>
        <section class="panel"><h2>Aplikasi iStore</h2><p>Kelola katalog produk dan stok tersimpan di database.</p><a href="{{ route('app') }}">Buka aplikasi toko →</a></section>
        <section class="cards" id="metrics"><div class="card"><span>Omzet hari ini</span><strong class="message">Memuat…</strong></div><div class="card"><span>Laba bersih hari ini</span><strong class="message">Memuat…</strong></div><div class="card"><span>Transaksi lunas</span><strong class="message">Memuat…</strong></div><div class="card"><span>Unit tersedia</span><strong class="message">Memuat…</strong></div></section>
        <section class="panel"><h2>Endpoint backend</h2><p>Semua data pada tautan berikut memerlukan login. Akses laporan dan pengelolaan produk/karyawan tertentu hanya diberikan kepada owner.</p><nav class="api-list"><a href="{{ route('api.dashboard') }}">Ringkasan dashboard (JSON)</a><a href="{{ route('api.products.index') }}">Daftar produk (JSON)</a><a href="{{ route('api.sales.index') }}">Riwayat transaksi (JSON)</a><a href="{{ route('api.stock.index') }}">Riwayat stok masuk (JSON)</a><a href="{{ route('api.cash-flows.index') }}">Arus kas (JSON)</a>@if(auth()->user()->isOwner())<a href="{{ route('api.reports') }}">Laporan laba (JSON)</a><a href="{{ route('api.employees.index') }}">Daftar karyawan (JSON)</a>@endif</nav></section>
        <section class="panel"><h2>Aturan laporan</h2><p>Laba bersih = omzet transaksi lunas − modal barang terjual − biaya operasional. Biaya pembelian stok tercatat di arus kas, sedangkan harga pokoknya mengurangi laba saat barang terjual. Harga pokok dihitung dengan rata-rata tertimbang biaya unit tersedia.</p></section>
    </main>
    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const formatRupiah = value => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value);
        fetch('{{ route('api.dashboard') }}', { headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf } })
            .then(response => response.json())
            .then(data => {
                const values = [formatRupiah(data.revenue), formatRupiah(data.net_profit), data.paid_sales_count, data.units_in_stock];
                document.querySelectorAll('#metrics .card strong').forEach((item, index) => item.textContent = values[index]);
            })
            .catch(() => document.querySelectorAll('#metrics .card strong').forEach(item => item.textContent = 'Belum tersedia'));
    </script>
</body>
</html>

