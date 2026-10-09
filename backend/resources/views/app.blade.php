<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="app-mode" content="backend" />
    <meta name="app-role" content="{{ auth()->user()->role }}" />
    <title>iStore — Sistem Operasional Toko</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="/istore/style.css" />
  </head>
  <body>
    <div class="app-shell">
      <aside class="sidebar" id="sidebar">
        <a class="brand" href="#dashboard" aria-label="iStore dashboard"
          ><span class="brand-mark">i</span
          ><span
            >istore<span class="brand-dot">.</span
            ><small>STORE OPERATIONS</small></span
          ></a
        >
        <div class="shop-switch">
          <span class="shop-avatar">I</span
          ><span class="shop-copy"
            ><b>iStore Jakarta</b><small>Cabang utama</small></span
          ><span class="chevrons">⌄</span>
        </div>
        <div class="nav-label">MENU UTAMA</div>
        <nav class="main-nav" aria-label="Navigasi utama">
          <button class="nav-item active" data-page="dashboard">
            <span class="nav-icon">▦</span>Ringkasan
          </button>
          <button class="nav-item" data-page="products">
            <span class="nav-icon">▣</span>Produk
            <span class="nav-count" id="product-count">40</span>
          </button>
          <button class="nav-item" data-page="sales">
            <span class="nav-icon">↗</span>Transaksi
          </button>
          <button class="nav-item" data-page="stock">
            <span class="nav-icon">⇄</span>Stok masuk
          </button>
          <button class="nav-item" data-page="cashflow">
            <span class="nav-icon">◷</span>Arus kas
          </button>
          <button class="nav-item" data-page="reports">
            <span class="nav-icon">▤</span>Laporan
          </button>
        </nav>
        <div class="nav-label management-label">PENGELOLAAN</div>
        <nav class="main-nav">
          <button class="nav-item" data-page="team">
            <span class="nav-icon">♙</span>Akun karyawan</button
          ><button class="nav-item" data-page="settings">
            <span class="nav-icon">⚙</span>Pengaturan
          </button>
        </nav>
        <div class="sidebar-bottom">
          <div class="help-card">
            <span class="help-symbol">?</span><b>Butuh bantuan?</b>
            <p>Lihat panduan penggunaan iStore.</p>
            <button id="help-button">Buka panduan <span>↗</span></button>
          </div>
          <div class="profile">
            <div class="profile-avatar">AD</div>
            <div class="profile-info">
              <b>{{ auth()->user()->name }}</b><small>{{ auth()->user()->role }}</small>
            </div>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button class="more-button" type="submit" aria-label="Keluar">↪</button>
            </form>
          </div>
        </div>
      </aside>
      <main class="main-area">
        <header class="topbar">
          <button class="mobile-menu" id="mobile-menu" aria-label="Buka menu">
            ☰
          </button>
          <div class="breadcrumbs">
            <span>iStore Jakarta</span><span class="crumb-slash">/</span
            ><b id="crumb-current">Ringkasan</b>
          </div>
          <div class="topbar-actions">
            <span class="today-label" id="today-label"></span
            ><button class="icon-button notification" aria-label="Notifikasi">
              ♧<i></i>
            </button>
            <div class="top-avatar">AD</div>
          </div>
        </header>
        <div class="page-wrap">
          <div class="sync-notice"><b>Katalog terhubung:</b> perubahan produk dan stok masuk disimpan ke database. Fitur transaksi, arus kas, dan laporan masih berupa data demo.</div>
          <section class="page active-page" id="page-dashboard">
            <div class="page-heading">
              <div>
                <div class="eyebrow">SELAMAT DATANG KEMBALI</div>
                <h1>Ringkasan toko <span class="wave">✳</span></h1>
                <p>Pantau performa dan aktivitas toko hari ini.</p>
              </div>
              <div class="heading-actions">
                <button class="button button-outline" id="export-button">
                  <span>⇩</span> Ekspor laporan</button
                ><button class="button button-primary" id="quick-sale">
                  <span>＋</span> Buat transaksi
                </button>
              </div>
            </div>
            <div class="filter-row">
              <div class="date-filter">
                <span>▦</span
                ><select id="date-range" aria-label="Rentang waktu">
                  <option>Hari ini, 9 Oktober 2026</option>
                  <option>7 hari terakhir</option>
                  <option>Bulan ini</option></select
                ><span class="down">⌄</span>
              </div>
              <div class="updated">
                <span class="live-dot"></span> Data diperbarui barusan
              </div>
            </div>
            <div class="metric-grid">
              <article class="metric-card">
                <div class="metric-head">
                  <span>Omzet penjualan</span
                  ><span class="metric-icon violet">↗</span>
                </div>
                <div class="metric-value" id="metric-revenue">
                  Rp 42.850.000
                </div>
                <div class="metric-foot">
                  <span class="trend up">↑ 12,8%</span
                  ><span> dibanding periode lalu</span>
                </div>
                <div class="sparkline spark-purple">
                  <i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i
                  ><i></i><i></i><i></i>
                </div>
              </article>
              <article class="metric-card">
                <div class="metric-head">
                  <span>Laba bersih</span
                  ><span class="metric-icon green">↗</span>
                </div>
                <div class="metric-value" id="metric-profit">Rp 5.420.000</div>
                <div class="metric-foot">
                  <span class="trend up">↑ 8,2%</span
                  ><span> dibanding periode lalu</span>
                </div>
                <div class="sparkline spark-green">
                  <i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i
                  ><i></i><i></i><i></i>
                </div>
              </article>
              <article class="metric-card">
                <div class="metric-head">
                  <span>Unit terjual</span
                  ><span class="metric-icon blue">▣</span>
                </div>
                <div class="metric-value" id="metric-units">
                  12 <small>unit</small>
                </div>
                <div class="metric-foot">
                  <span class="trend up">↑ 4 unit</span
                  ><span> dibanding periode lalu</span>
                </div>
                <div class="sparkline spark-blue">
                  <i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i
                  ><i></i><i></i><i></i>
                </div>
              </article>
              <article class="metric-card">
                <div class="metric-head">
                  <span>Total persediaan</span
                  ><span class="metric-icon amber">▤</span>
                </div>
                <div class="metric-value" id="metric-stock">
                  68 <small>unit</small>
                </div>
                <div class="metric-foot">
                  <span class="stock-note"><b>6 produk</b> stok menipis</span
                  ><a href="#products" data-goto="products">Lihat stok →</a>
                </div>
                <div class="stock-bars">
                  <span style="height: 35%"></span
                  ><span style="height: 55%"></span
                  ><span style="height: 40%"></span
                  ><span style="height: 68%"></span
                  ><span style="height: 53%"></span
                  ><span style="height: 85%"></span
                  ><span style="height: 63%"></span
                  ><span style="height: 100%"></span
                  ><span style="height: 74%"></span
                  ><span style="height: 91%"></span>
                </div>
              </article>
            </div>
            <div class="dashboard-grid">
              <section class="panel sales-panel">
                <div class="panel-heading">
                  <div>
                    <h2>Performa penjualan</h2>
                    <p>Omzet dan laba bersih toko</p>
                  </div>
                  <select class="small-select" aria-label="Periode grafik">
                    <option>7 hari terakhir</option>
                    <option>30 hari terakhir</option>
                  </select>
                </div>
                <div class="chart-legend">
                  <span><i class="legend-dot purple-dot"></i>Omzet</span
                  ><span><i class="legend-dot green-dot"></i>Laba bersih</span>
                </div>
                <div class="chart-area">
                  <div class="y-labels">
                    <span>Rp 50 jt</span><span>Rp 35 jt</span
                    ><span>Rp 20 jt</span><span>Rp 5 jt</span>
                  </div>
                  <div class="chart">
                    <div class="gridline g1"></div>
                    <div class="gridline g2"></div>
                    <div class="gridline g3"></div>
                    <div class="gridline g4"></div>
                    <svg
                      viewBox="0 0 700 190"
                      preserveAspectRatio="none"
                      aria-label="Grafik penjualan tujuh hari"
                    >
                      <defs>
                        <linearGradient
                          id="fillRevenue"
                          x1="0"
                          x2="0"
                          y1="0"
                          y2="1"
                        >
                          <stop
                            offset="0"
                            stop-color="#8174ef"
                            stop-opacity=".18"
                          />
                          <stop
                            offset="1"
                            stop-color="#8174ef"
                            stop-opacity="0"
                          />
                        </linearGradient>
                      </defs>
                      <path
                        class="area-path"
                        d="M0 133 C42 120 48 104 100 112 S163 145 200 101 S264 91 300 99 S365 58 400 76 S465 115 500 68 S564 78 600 47 S665 32 700 20 L700 190 L0 190Z"
                      />
                      <path
                        class="revenue-path"
                        d="M0 133 C42 120 48 104 100 112 S163 145 200 101 S264 91 300 99 S365 58 400 76 S465 115 500 68 S564 78 600 47 S665 32 700 20"
                      />
                      <path
                        class="profit-path"
                        d="M0 164 C42 157 48 149 100 152 S163 163 200 143 S264 137 300 141 S365 122 400 129 S465 150 500 125 S564 132 600 111 S665 106 700 92"
                      />
                      <circle cx="700" cy="20" r="4" class="chart-point" />
                    </svg>
                    <div class="x-labels">
                      <span>Sen, 5</span><span>Sel, 6</span><span>Rab, 7</span
                      ><span>Kam, 8</span><span>Jum, 9</span><span>Sab, 10</span
                      ><span>Min, 11</span>
                    </div>
                  </div>
                </div>
              </section>
              <section class="panel payment-panel">
                <div class="panel-heading">
                  <div>
                    <h2>Ringkasan pembayaran</h2>
                    <p>Metode pembayaran hari ini</p>
                  </div>
                  <button class="dots-button" aria-label="Opsi">•••</button>
                </div>
                <div class="donut-wrap">
                  <div class="donut">
                    <div><b>12</b><small>transaksi</small></div>
                  </div>
                </div>
                <div class="payment-list">
                  <div>
                    <span class="payment-name"
                      ><i class="pay-dot pay-cash"></i>Tunai</span
                    ><b>Rp 18.250.000</b><span class="pay-percent">42,6%</span>
                  </div>
                  <div>
                    <span class="payment-name"
                      ><i class="pay-dot pay-transfer"></i>Transfer</span
                    ><b>Rp 12.800.000</b><span class="pay-percent">29,9%</span>
                  </div>
                  <div>
                    <span class="payment-name"
                      ><i class="pay-dot pay-qris"></i>QRIS</span
                    ><b>Rp 8.600.000</b><span class="pay-percent">20,1%</span>
                  </div>
                  <div>
                    <span class="payment-name"
                      ><i class="pay-dot pay-card"></i>Kartu</span
                    ><b>Rp 3.200.000</b><span class="pay-percent">7,4%</span>
                  </div>
                </div>
              </section>
            </div>
            <section class="panel table-panel">
              <div class="panel-heading table-title">
                <div>
                  <h2>Transaksi terbaru</h2>
                  <p>Aktivitas penjualan yang baru dicatat</p>
                </div>
                <button class="text-button" data-goto="sales">
                  Lihat semua transaksi <span>→</span>
                </button>
              </div>
              <div class="table-scroll">
                <table>
                  <thead>
                    <tr>
                      <th>ID TRANSAKSI</th>
                      <th>PRODUK</th>
                      <th>KARYAWAN</th>
                      <th>WAKTU</th>
                      <th>PEMBAYARAN</th>
                      <th>TOTAL</th>
                    </tr>
                  </thead>
                  <tbody id="recent-sales"></tbody>
                </table>
              </div>
            </section>
          </section>
          <section class="page" id="page-products">
            <div class="page-heading">
              <div>
                <div class="eyebrow">KATALOG & PERSEDIAAN</div>
                <h1>Produk</h1>
                <p>Kelola varian iPhone, harga, stok, dan IMEI unit.</p>
              </div>
              <button class="button button-primary" id="add-product">
                <span>＋</span> Tambah produk
              </button>
            </div>
            <div class="product-summary">
              <div>
                <span class="summary-icon">▣</span
                ><span
                  ><b id="catalog-total">40 varian</b
                  ><small>Total katalog</small></span
                >
              </div>
              <div>
                <span class="summary-icon orange-icon">⚠</span
                ><span><b id="low-stock-count">0 varian</b><small>Stok menipis</small></span>
              </div>
              <div>
                <span class="summary-icon green-icon">✓</span
                ><span
                  ><b>Semua tersinkron</b><small>Status inventaris</small></span
                >
              </div>
            </div>
            <div class="panel product-panel">
              <div class="toolbar">
                <div class="search-box">
                  <span>⌕</span
                  ><input
                    id="product-search"
                    placeholder="Cari nama produk atau IMEI"
                    aria-label="Cari produk"
                  />
                </div>
                <div class="toolbar-right">
                  <select id="model-filter">
                    <option value="all">Semua model</option>
                    <option>iPhone 14</option>
                    <option>iPhone 15</option>
                    <option>iPhone 16</option>
                    <option>iPhone 17</option>
                    <option>iPhone 18</option></select
                  ><select id="capacity-filter">
                    <option value="all">Semua kapasitas</option>
                    <option>256 GB</option>
                    <option>512 GB</option></select
                  ><button class="button button-outline" id="filter-button">
                    ☷ Filter
                  </button>
                </div>
              </div>
              <div class="table-scroll">
                <table>
                  <thead>
                    <tr>
                      <th>PRODUK</th>
                      <th>IMEI</th>
                      <th>HARGA JUAL</th>
                      <th>MODAL / UNIT</th>
                      <th>STOK</th>
                      <th>STATUS</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody id="products-table"></tbody>
                </table>
              </div>
              <div class="table-pagination">
                <span
                  >Menampilkan <b id="showing-count">10</b> dari
                  <b>40</b> varian</span
                >
                <div>
                  <button disabled>← Sebelumnya</button
                  ><button class="page-number">1</button><button>2</button
                  ><button>3</button><button>4</button
                  ><button>Selanjutnya →</button>
                </div>
              </div>
            </div>
          </section>
          <section class="page" id="page-sales">
            <div class="page-heading">
              <div>
                <div class="eyebrow">PENJUALAN</div>
                <h1>Transaksi</h1>
                <p>Catat penjualan dan pantau status pembayaran.</p>
              </div>
              <button class="button button-primary" id="new-sale">
                <span>＋</span> Buat transaksi
              </button>
            </div>
            <div class="product-summary sales-summary">
              <div>
                <span class="summary-icon">↗</span
                ><span><b>12 transaksi</b><small>Hari ini</small></span>
              </div>
              <div>
                <span class="summary-icon green-icon">✓</span
                ><span><b>10 lunas</b><small>Pembayaran selesai</small></span>
              </div>
              <div>
                <span class="summary-icon orange-icon">◷</span
                ><span
                  ><b>2 menunggu</b><small>Menunggu pembayaran</small></span
                >
              </div>
            </div>
            <div class="panel table-panel">
              <div class="toolbar">
                <div class="search-box">
                  <span>⌕</span
                  ><input
                    id="sales-search"
                    placeholder="Cari ID transaksi atau produk"
                  />
                </div>
                <div class="toolbar-right">
                  <select>
                    <option>Semua status</option>
                    <option>Lunas</option>
                    <option>Menunggu pembayaran</option></select
                  ><select>
                    <option>Hari ini</option>
                    <option>7 hari terakhir</option>
                    <option>Bulan ini</option>
                  </select>
                </div>
              </div>
              <div class="table-scroll">
                <table>
                  <thead>
                    <tr>
                      <th>ID TRANSAKSI</th>
                      <th>PRODUK</th>
                      <th>KARYAWAN</th>
                      <th>WAKTU</th>
                      <th>METODE</th>
                      <th>STATUS</th>
                      <th>TOTAL</th>
                    </tr>
                  </thead>
                  <tbody id="sales-table"></tbody>
                </table>
              </div>
            </div>
          </section>
          <section class="page" id="page-stock">
            <div class="page-heading">
              <div>
                <div class="eyebrow">PERSEDIAAN</div>
                <h1>Stok masuk</h1>
                <p>Catat unit baru dan harga beli sesuai nota pemasok.</p>
              </div>
              <button class="button button-primary" id="add-stock">
                <span>＋</span> Catat stok masuk
              </button>
            </div>
            <div class="product-summary">
              <div>
                <span class="summary-icon">⇄</span
                ><span><b id="stock-entry-count">0 penerimaan</b><small>Riwayat stok</small></span>
              </div>
              <div>
                <span class="summary-icon green-icon">▣</span
                ><span
                  ><b id="stock-units-total">0 unit masuk</b><small>Persediaan bertambah</small></span
                >
              </div>
              <div>
                <span class="summary-icon">Rp</span
                ><span
                  ><b id="stock-value-total">Rp 0</b><small>Nilai pembelian stok</small></span
                >
              </div>
            </div>
            <div class="panel table-panel">
              <div class="panel-heading table-title">
                <div>
                  <h2>Riwayat stok masuk</h2>
                  <p>Catatan penerimaan barang terbaru</p>
                </div>
              </div>
              <div class="table-scroll">
                <table>
                  <thead>
                    <tr>
                      <th>ID PENERIMAAN</th>
                      <th>PRODUK</th>
                      <th>IMEI UNIT</th>
                      <th>JUMLAH</th>
                      <th>HARGA BELI/UNIT</th>
                      <th>TANGGAL</th>
                      <th>DICATAT OLEH</th>
                    </tr>
                  </thead>
                  <tbody id="stock-table"></tbody>
                </table>
              </div>
            </div>
          </section>
          <section class="page" id="page-cashflow">
            <div class="page-heading">
              <div>
                <div class="eyebrow">KEUANGAN</div>
                <h1>Arus kas</h1>
                <p>Pantau uang masuk dan keluar toko.</p>
              </div>
              <button class="button button-primary" id="add-cash">
                <span>＋</span> Catat arus kas
              </button>
            </div>
            <div class="metric-grid cash-metrics">
              <article class="metric-card">
                <div class="metric-head">
                  Total pemasukan <span class="metric-icon green">↓</span>
                </div>
                <div class="metric-value">Rp 52.750.000</div>
                <div class="metric-foot">Periode Oktober 2026</div>
              </article>
              <article class="metric-card">
                <div class="metric-head">
                  Total pengeluaran <span class="metric-icon coral">↑</span>
                </div>
                <div class="metric-value">Rp 38.200.000</div>
                <div class="metric-foot">
                  Termasuk pembelian stok & operasional
                </div>
              </article>
              <article class="metric-card">
                <div class="metric-head">
                  Saldo kas bersih <span class="metric-icon blue">◈</span>
                </div>
                <div class="metric-value">Rp 14.550.000</div>
                <div class="metric-foot">Pemasukan dikurangi pengeluaran</div>
              </article>
              <article class="metric-card">
                <div class="metric-head">
                  Transaksi kas <span class="metric-icon violet">≡</span>
                </div>
                <div class="metric-value">26 <small>catatan</small></div>
                <div class="metric-foot">Periode Oktober 2026</div>
              </article>
            </div>
            <div class="panel table-panel">
              <div class="panel-heading table-title">
                <div>
                  <h2>Catatan arus kas</h2>
                  <p>Semua pemasukan dan pengeluaran</p>
                </div>
                <div class="toolbar-right">
                  <select>
                    <option>Semua jenis</option>
                    <option>Pemasukan</option>
                    <option>Pengeluaran</option></select
                  ><select>
                    <option>Oktober 2026</option>
                    <option>September 2026</option>
                  </select>
                </div>
              </div>
              <div class="table-scroll">
                <table>
                  <thead>
                    <tr>
                      <th>TANGGAL</th>
                      <th>KETERANGAN</th>
                      <th>KATEGORI</th>
                      <th>DICATAT OLEH</th>
                      <th>JUMLAH</th>
                      <th>JENIS</th>
                    </tr>
                  </thead>
                  <tbody id="cash-table"></tbody>
                </table>
              </div>
            </div>
          </section>
          <section class="page" id="page-reports">
            <div class="page-heading">
              <div>
                <div class="eyebrow">ANALISIS BISNIS</div>
                <h1>Laporan</h1>
                <p>Ringkasan penjualan, omzet, laba bersih, dan persediaan.</p>
              </div>
              <button class="button button-outline" id="report-export">
                ⇩ Ekspor laporan
              </button>
            </div>
            <div class="filter-row">
              <div class="date-filter">
                ▦
                <select>
                  <option>Oktober 2026</option>
                  <option>September 2026</option></select
                ><span class="down">⌄</span>
              </div>
            </div>
            <div class="metric-grid report-metrics">
              <article class="metric-card">
                <div class="metric-head">Omzet</div>
                <div class="metric-value">Rp 286.450.000</div>
                <div class="metric-foot">
                  <span class="trend up">↑ 14,2%</span> dari bulan lalu
                </div>
              </article>
              <article class="metric-card">
                <div class="metric-head">Modal barang terjual</div>
                <div class="metric-value">Rp 251.730.000</div>
                <div class="metric-foot">
                  Berdasarkan harga modal saat stok masuk
                </div>
              </article>
              <article class="metric-card">
                <div class="metric-head">Biaya operasional</div>
                <div class="metric-value">Rp 12.800.000</div>
                <div class="metric-foot">Di luar pembelian stok</div>
              </article>
              <article class="metric-card">
                <div class="metric-head">Laba bersih</div>
                <div class="metric-value">Rp 21.920.000</div>
                <div class="metric-foot">Omzet − modal terjual − biaya</div>
              </article>
            </div>
            <div class="panel report-note">
              <span class="note-icon">i</span>
              <div>
                <b>Metode perhitungan</b>
                <p>
                  Laba bersih dihitung dari omzet transaksi lunas dikurangi
                  harga modal rata-rata tertimbang unit yang terjual dan biaya
                  operasional. Pembelian stok masuk ke arus kas serta
                  persediaan; bukan langsung dihitung sebagai biaya laba.
                </p>
              </div>
            </div>
            <div class="panel table-panel">
              <div class="panel-heading table-title">
                <div>
                  <h2>Ringkasan produk terjual</h2>
                  <p>Produk terlaris selama Oktober</p>
                </div>
              </div>
              <div class="table-scroll">
                <table>
                  <thead>
                    <tr>
                      <th>PRODUK</th>
                      <th>UNIT TERJUAL</th>
                      <th>OMZET</th>
                      <th>HARGA MODAL</th>
                      <th>LABA KOTOR</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <div class="product-cell">
                          <div class="mini-phone">▯</div>
                          <div>
                            <b>iPhone 17 Pro Max</b
                            ><small>512 GB · White</small>
                          </div>
                        </div>
                      </td>
                      <td>8 unit</td>
                      <td>Rp 235.992.000</td>
                      <td>Rp 213.400.000</td>
                      <td class="positive">Rp 22.592.000</td>
                    </tr>
                    <tr>
                      <td>
                        <div class="product-cell">
                          <div class="mini-phone pink-phone">▯</div>
                          <div>
                            <b>iPhone 16</b><small>256 GB · Pink</small>
                          </div>
                        </div>
                      </td>
                      <td>6 unit</td>
                      <td>Rp 98.994.000</td>
                      <td>Rp 89.100.000</td>
                      <td class="positive">Rp 9.894.000</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </section>
          <section class="page" id="page-team">
            <div class="page-heading">
              <div>
                <div class="eyebrow">AKSES PENGGUNA</div>
                <h1>Akun karyawan</h1>
                <p>Kelola pengguna dan hak akses ke sistem.</p>
              </div>
              <button class="button button-primary" id="add-employee">
                <span>＋</span> Tambah karyawan
              </button>
            </div>
            <div class="panel table-panel">
              <div class="panel-heading table-title">
                <div>
                  <h2>Tim iStore</h2>
                  <p>3 pengguna terdaftar</p>
                </div>
              </div>
              <div class="table-scroll">
                <table>
                  <thead>
                    <tr>
                      <th>NAMA</th>
                      <th>EMAIL</th>
                      <th>PERAN</th>
                      <th>STATUS</th>
                      <th>AKTIVITAS TERAKHIR</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <div class="product-cell">
                          <div class="profile-avatar">AD</div>
                          <div><b>Aditya Pratama</b><small>Owner</small></div>
                        </div>
                      </td>
                      <td>aditya@istore.demo</td>
                      <td><span class="role-pill owner-role">Owner</span></td>
                      <td>
                        <span class="status-pill status-paid">Aktif</span>
                      </td>
                      <td>Hari ini, 09.12</td>
                      <td>•••</td>
                    </tr>
                    <tr>
                      <td>
                        <div class="product-cell">
                          <div class="profile-avatar employee-avatar">NR</div>
                          <div><b>Nadia Rahma</b><small>Karyawan</small></div>
                        </div>
                      </td>
                      <td>nadia@istore.demo</td>
                      <td><span class="role-pill">Karyawan</span></td>
                      <td>
                        <span class="status-pill status-paid">Aktif</span>
                      </td>
                      <td>Hari ini, 09.35</td>
                      <td>•••</td>
                    </tr>
                    <tr>
                      <td>
                        <div class="product-cell">
                          <div class="profile-avatar employee-avatar">FA</div>
                          <div><b>Fajar Akbar</b><small>Karyawan</small></div>
                        </div>
                      </td>
                      <td>fajar@istore.demo</td>
                      <td><span class="role-pill">Karyawan</span></td>
                      <td>
                        <span class="status-pill status-paid">Aktif</span>
                      </td>
                      <td>Kemarin, 18.20</td>
                      <td>•••</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="panel report-note">
              <span class="note-icon">i</span>
              <div>
                <b>Hak akses karyawan</b>
                <p>
                  Karyawan dapat mencatat penjualan, stok masuk, dan arus kas.
                  Pengaturan harga jual, laporan laba, dan manajemen akun hanya
                  tersedia untuk owner.
                </p>
              </div>
            </div>
          </section>
          <section class="page" id="page-settings">
            <div class="page-heading">
              <div>
                <div class="eyebrow">PREFERENSI TOKO</div>
                <h1>Pengaturan</h1>
                <p>Atur identitas toko dan preferensi sistem.</p>
              </div>
            </div>
            <div class="settings-grid">
              <div class="panel settings-card">
                <div class="settings-card-head">
                  <span class="summary-icon">⌂</span>
                  <div>
                    <h2>Informasi toko</h2>
                    <p>Identitas yang digunakan dalam sistem.</p>
                  </div>
                </div>
                <label>Nama toko<input value="iStore Jakarta" /></label
                ><label
                  >Alamat toko<input
                    value="Jl. Sudirman No. 21, Jakarta" /></label
                ><label
                  >Zona waktu<select>
                    <option>Asia/Jakarta (WIB)</option>
                  </select></label
                ><button class="button button-primary">Simpan perubahan</button>
              </div>
              <div class="panel settings-card">
                <div class="settings-card-head">
                  <span class="summary-icon green-icon">◈</span>
                  <div>
                    <h2>Perhitungan laba</h2>
                    <p>Pengaturan harga modal persediaan.</p>
                  </div>
                </div>
                <label
                  >Metode harga modal<select>
                    <option>Rata-rata tertimbang</option>
                  </select></label
                >
                <div class="settings-info">
                  Harga modal dicatat karyawan saat stok masuk. Sistem
                  menghitung rata-rata modal berdasarkan jumlah unit dan nilai
                  pembelian.
                </div>
              </div>
              <div class="panel settings-card">
                <div class="settings-card-head">
                  <span class="summary-icon orange-icon">▣</span>
                  <div>
                    <h2>Data demo</h2>
                    <p>Data untuk kebutuhan presentasi tugas.</p>
                  </div>
                </div>
                <div class="settings-info">
                  Harga modal, stok, foto, deskripsi, transaksi, dan IMEI dalam
                  prototipe ini merupakan data simulasi. IMEI demo tidak mengacu
                  pada perangkat sungguhan.
                </div>
                <button class="button button-outline" id="reset-demo">
                  Reset data demo
                </button>
              </div>
            </div>
          </section>
        </div>
      </main>
    </div>
    <div class="toast" id="toast"></div>
    <div class="modal-backdrop" id="modal-backdrop">
      <div
        class="modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-title"
      >
        <div class="modal-header">
          <div>
            <div class="eyebrow">ISTORE</div>
            <h2 id="modal-title">Buat transaksi</h2>
          </div>
          <button class="close-modal" id="close-modal" aria-label="Tutup">
            ×
          </button>
        </div>
        <form id="modal-form">
          <div id="modal-fields"></div>
          <div class="modal-footer">
            <button
              type="button"
              class="button button-outline"
              id="cancel-modal"
            >
              Batal</button
            ><button
              type="submit"
              class="button button-primary"
              id="save-modal"
            >
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
    <script src="/istore/app.js"></script>
  </body>
</html>

