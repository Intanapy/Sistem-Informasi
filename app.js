// Data awal untuk demo. Semua IMEI dan data stok di bagian ini bersifat fiktif.
const seedProducts = () => {
  const series = [14, 15, 16, 17, 18],
    models = ["", " Pro Max"];
  const p256 = {
    14: 11999000,
    15: 15499000,
    16: 16499000,
    17: 20499000,
    18: 29499000,
  };
  const colors = ["White", "Pink"];
  let n = 0;
  return series.flatMap((year) =>
    models.flatMap((suffix) =>
      [256, 512].flatMap((cap) =>
        colors.map((color) => {
          n++;
          let base = p256[year] || 13999000;
          let pro = suffix ? 1.18 : 1;
          let storage = cap === 512 ? 1.18 : 1;
          let retail = Math.round((base * pro * storage) / 1000) * 1000;
          let qty = [3, 1, 4, 2, 5, 2, 3, 1][n % 8];
          return {
            id: n,
            model: `iPhone ${year}${suffix}`,
            capacity: `${cap} GB`,
            color,
            price: retail,
            cost: Math.round((retail * 0.9) / 1000) * 1000,
            stock: qty,
            image: "phone",
            description: `Apple iPhone ${year}${suffix}, ${cap} GB, warna ${color === "White" ? "Putih" : "Pink"}. Data demonstrasi untuk sistem inventaris iStore.`,
            imei: `35824005${String(year).padStart(2, "0")}${String(n).padStart(5, "0")}`,
            status: qty <= 1 ? "Stok menipis" : "Tersedia",
          };
        }),
      ),
    ),
  );
};
const seedSales = [
  {
    id: "TRX-261009-012",
    product: "iPhone 17 Pro Max",
    variant: "512 GB · White",
    employee: "Nadia Rahma",
    time: "Hari ini, 10.42",
    method: "QRIS",
    status: "Lunas",
    total: 29499000,
    cost: 26549000,
  },
  {
    id: "TRX-261009-011",
    product: "iPhone 16",
    variant: "256 GB · Pink",
    employee: "Fajar Akbar",
    time: "Hari ini, 10.18",
    method: "Transfer",
    status: "Lunas",
    total: 16499000,
    cost: 14849000,
  },
  {
    id: "TRX-261009-010",
    product: "iPhone 15 Pro Max",
    variant: "256 GB · White",
    employee: "Nadia Rahma",
    time: "Hari ini, 09.56",
    method: "Tunai",
    status: "Menunggu pembayaran",
    total: 18289000,
    cost: 16460000,
  },
  {
    id: "TRX-261009-009",
    product: "iPhone 18 Pro Max",
    variant: "256 GB · Pink",
    employee: "Fajar Akbar",
    time: "Hari ini, 09.21",
    method: "Kartu",
    status: "Lunas",
    total: 34809000,
    cost: 31328000,
  },
  {
    id: "TRX-261009-008",
    product: "iPhone 17",
    variant: "512 GB · White",
    employee: "Nadia Rahma",
    time: "Hari ini, 08.54",
    method: "Transfer",
    status: "Lunas",
    total: 24149000,
    cost: 21734000,
  },
];
const seedStock = [
  {
    id: "STK-261008-008",
    product: "iPhone 17 Pro Max",
    variant: "512 GB · White",
    imei: "35824005261700001",
    qty: 2,
    cost: 26549000,
    date: "8 Okt 2026",
    employee: "Nadia Rahma",
  },
  {
    id: "STK-261007-007",
    product: "iPhone 16",
    variant: "256 GB · Pink",
    imei: "35824005261600002",
    qty: 4,
    cost: 14849000,
    date: "7 Okt 2026",
    employee: "Fajar Akbar",
  },
  {
    id: "STK-261006-006",
    product: "iPhone 18 Pro Max",
    variant: "256 GB · White",
    imei: "35824005261800003",
    qty: 3,
    cost: 26549000,
    date: "6 Okt 2026",
    employee: "Nadia Rahma",
  },
];
const seedCash = [
  {
    date: "9 Okt 2026",
    note: "Penjualan #TRX-261009-012",
    category: "Penjualan",
    employee: "Nadia Rahma",
    amount: 29499000,
    type: "Pemasukan",
  },
  {
    date: "8 Okt 2026",
    note: "Pembelian stok iPhone 17 Pro Max",
    category: "Pembelian stok",
    employee: "Nadia Rahma",
    amount: 53098000,
    type: "Pengeluaran",
  },
  {
    date: "7 Okt 2026",
    note: "Tagihan internet toko",
    category: "Operasional",
    employee: "Fajar Akbar",
    amount: 650000,
    type: "Pengeluaran",
  },
  {
    date: "6 Okt 2026",
    note: "Penjualan #TRX-261006-004",
    category: "Penjualan",
    employee: "Fajar Akbar",
    amount: 20499000,
    type: "Pemasukan",
  },
];
// Penyimpanan browser membuat perubahan demo tetap ada saat halaman dibuka lagi.
const get = (key, fallback) => {
  try {
    return JSON.parse(localStorage.getItem("istore-" + key)) ?? fallback;
  } catch {
    return fallback;
  }
};
const backendMode =
    document.querySelector('meta[name="app-mode"]')?.content === "backend",
  isOwner = document.querySelector('meta[name="app-role"]')?.content === "owner";
let products = backendMode ? [] : get("products", seedProducts()),
  sales = get("sales", seedSales),
  stocks = backendMode ? [] : get("stocks", seedStock),
  cash = get("cash", seedCash);
const save = () => {
  if (!backendMode) {
    localStorage.setItem("istore-products", JSON.stringify(products));
    localStorage.setItem("istore-stocks", JSON.stringify(stocks));
  }
  localStorage.setItem("istore-sales", JSON.stringify(sales));
  localStorage.setItem("istore-cash", JSON.stringify(cash));
};
const rupiah = (n) => "Rp " + Number(n || 0).toLocaleString("id-ID");
const byId = (id) => document.getElementById(id);
const escapeHtml = (value) =>
  String(value ?? "").replace(/[&<>"']/g, (character) => {
    const entities = {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#39;",
    };
    return entities[character];
  });
let toastTimer;
function toast(text) {
  const el = byId("toast");
  el.textContent = text;
  el.classList.add("show");
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => el.classList.remove("show"), 2600);
}

// Aplikasi Laravel memakai session login yang sama untuk membaca dan menyimpan katalog.
async function apiRequest(path, options = {}) {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
  const response = await fetch(`/api/${path}`, {
    credentials: "same-origin",
    ...options,
    headers: {
      Accept: "application/json",
      ...(options.body ? { "Content-Type": "application/json" } : {}),
      ...(csrf ? { "X-CSRF-TOKEN": csrf } : {}),
      ...options.headers,
    },
  });
  const data = await response.json().catch(() => ({}));
  if (!response.ok) {
    const validationMessage = data.errors
      ? Object.values(data.errors).flat()[0]
      : null;
    throw new Error(validationMessage || data.message || "Permintaan gagal.");
  }
  return data;
}

function mapApiVariant(variant) {
  const units = variant.available_units || [];
  const averageCost = units.length
    ? units.reduce((sum, unit) => sum + Number(unit.purchase_cost || 0), 0) /
      units.length
    : 0;
  const stock = Number(variant.stock || 0);
  return {
    id: Number(variant.id),
    model: variant.product?.name || "iPhone",
    capacity: `${variant.capacity_gb} GB`,
    color: variant.color,
    price: Number(variant.selling_price),
    cost: Math.round(averageCost),
    stock,
    image: "phone",
    description: variant.product?.description || "",
    imei: units[0]?.imei || "—",
    status: stock <= 1 ? "Stok menipis" : "Tersedia",
  };
}

function mapApiStockEntry(entry) {
  const variant = entry.variant || {};
  const units = entry.units || [];
  return {
    id: entry.number,
    product: variant.product?.name || "Produk",
    variant: `${variant.capacity_gb || "-"} GB · ${variant.color || "-"}`,
    imei: units[0]?.imei || "—",
    qty: Number(entry.quantity || 0),
    cost: Number(entry.unit_cost || 0),
    date: new Intl.DateTimeFormat("id-ID", {
      day: "numeric",
      month: "short",
      year: "numeric",
    }).format(new Date(entry.created_at)),
    employee: entry.employee?.name || "-",
  };
}

async function refreshBackendCatalog() {
  const [variantData, stockData] = await Promise.all([
    apiRequest("products"),
    apiRequest("stock-entries"),
  ]);
  products = variantData.map(mapApiVariant);
  const stockEntries = stockData.data || stockData;
  stocks = stockEntries.map(mapApiStockEntry);
  const unitCount = stockEntries.reduce(
    (total, entry) => total + Number(entry.quantity || 0),
    0,
  );
  const purchaseValue = stockEntries.reduce(
    (total, entry) => total + Number(entry.total_cost || 0),
    0,
  );
  byId("stock-entry-count").textContent =
    `${stockData.total ?? stockEntries.length} penerimaan`;
  byId("stock-units-total").textContent = `${unitCount} unit masuk`;
  byId("stock-value-total").textContent = rupiah(purchaseValue);
  render();
}
// Navigasi halaman dan judul breadcrumb.
const pageNames = {
  dashboard: "Ringkasan",
  products: "Produk",
  sales: "Transaksi",
  stock: "Stok masuk",
  cashflow: "Arus kas",
  reports: "Laporan",
  team: "Akun karyawan",
  settings: "Pengaturan",
};
function go(page) {
  document
    .querySelectorAll(".page")
    .forEach((el) => el.classList.remove("active-page"));
  byId("page-" + page)?.classList.add("active-page");
  document
    .querySelectorAll(".nav-item")
    .forEach((el) => el.classList.toggle("active", el.dataset.page === page));
  byId("crumb-current").textContent = pageNames[page] || "Ringkasan";
  byId("sidebar").classList.remove("mobile-open");
  if (location.hash !== "#" + page) history.replaceState(null, "", "#" + page);
  render();
}
document
  .querySelectorAll(".nav-item")
  .forEach((btn) => btn.addEventListener("click", () => go(btn.dataset.page)));
document
  .querySelectorAll("[data-goto]")
  .forEach((btn) => btn.addEventListener("click", () => go(btn.dataset.goto)));
byId("mobile-menu").addEventListener("click", () =>
  byId("sidebar").classList.toggle("mobile-open"),
);
byId("today-label").textContent = new Intl.DateTimeFormat("id-ID", {
  weekday: "long",
  day: "numeric",
  month: "long",
  year: "numeric",
}).format(new Date());
// Render tabel untuk katalog, transaksi, penerimaan stok, dan arus kas.
function productPhoto(color) {
  return `<div class="mini-phone ${color === "Pink" ? "pink-phone" : ""}" title="Foto ilustrasi iPhone">▯</div>`;
}
function renderProducts() {
  let query = (byId("product-search")?.value || "").toLowerCase(),
    model = byId("model-filter")?.value || "all",
    capacity = byId("capacity-filter")?.value || "all";
  let filtered = products.filter(
    (p) =>
      (p.model + " " + p.capacity + " " + p.color + " " + p.imei)
        .toLowerCase()
        .includes(query) &&
      (model === "all" || p.model === model) &&
      (capacity === "all" || p.capacity === capacity),
  );
  const visible = filtered.slice(0, 10);
  byId("products-table").innerHTML =
    visible
      .map(
        (p) =>
          `<tr><td><div class="product-cell">${productPhoto(p.color)}<div><b>${escapeHtml(p.model)}</b><small>${escapeHtml(p.capacity)} · ${p.color === "White" ? "Putih" : "Pink"}</small></div></div></td><td class="transaction-id">${escapeHtml(p.imei)}</td><td><b>${rupiah(p.price)}</b></td><td>${rupiah(p.cost)}</td><td><b>${p.stock}</b> unit</td><td><span class="status-pill ${p.stock <= 1 ? "status-pending" : "status-paid"}">${p.stock <= 1 ? "Stok menipis" : "Tersedia"}</span></td><td>${!backendMode || isOwner ? `<button class="more-button product-edit" data-id="${p.id}" aria-label="Edit produk">•••</button>` : ""}</td></tr>`,
      )
      .join("") ||
    '<tr><td colspan="7" class="muted-cell">Produk tidak ditemukan.</td></tr>';
  byId("showing-count").textContent = visible.length;
  byId("catalog-total").textContent = products.length + " varian";
  byId("product-count").textContent = products.length;
  const lowStockCount = products.filter((product) => product.stock <= 1).length;
  if (byId("low-stock-count")) {
    byId("low-stock-count").textContent = `${lowStockCount} varian`;
  }
  document.querySelectorAll(".product-edit").forEach((btn) =>
    btn.addEventListener("click", () =>
      openForm(
        "editProduct",
        products.find((p) => p.id === Number(btn.dataset.id)),
      ),
    ),
  );
}
function renderSales() {
  const rows = sales
    .map(
      (s) =>
        `<tr><td class="transaction-id">${s.id}</td><td><div class="product-cell">${productPhoto(s.variant.includes("Pink") ? "Pink" : "White")}<div><b>${s.product}</b><small>${s.variant}</small></div></div></td><td>${s.employee}</td><td>${s.time}</td><td>${s.method}</td><td><span class="status-pill ${s.status === "Lunas" ? "status-paid" : "status-pending"}">${s.status}</span></td><td><b>${rupiah(s.total)}</b></td></tr>`,
    )
    .join("");
  byId("sales-table").innerHTML = rows;
  byId("recent-sales").innerHTML = sales
    .slice(0, 4)
    .map(
      (s) =>
        `<tr><td class="transaction-id">${s.id}</td><td><div class="product-cell">${productPhoto(s.variant.includes("Pink") ? "Pink" : "White")}<div><b>${s.product}</b><small>${s.variant}</small></div></div></td><td>${s.employee}</td><td>${s.time}</td><td><span class="status-pill ${s.status === "Lunas" ? "status-paid" : "status-pending"}">${s.status}</span></td><td><b>${rupiah(s.total)}</b></td></tr>`,
    )
    .join("");
}
function renderStock() {
  byId("stock-table").innerHTML = stocks
    .map(
      (s) =>
        `<tr><td class="transaction-id">${escapeHtml(s.id)}</td><td><div class="product-cell">${productPhoto(s.variant.includes("Pink") ? "Pink" : "White")}<div><b>${escapeHtml(s.product)}</b><small>${escapeHtml(s.variant)}</small></div></div></td><td class="transaction-id">${escapeHtml(s.imei)}</td><td><b>${s.qty}</b> unit</td><td>${rupiah(s.cost)}</td><td>${escapeHtml(s.date)}</td><td>${escapeHtml(s.employee)}</td></tr>`,
    )
    .join("");
}
function renderCash() {
  byId("cash-table").innerHTML = cash
    .map(
      (c) =>
        `<tr><td>${c.date}</td><td><b>${c.note}</b></td><td>${c.category}</td><td>${c.employee}</td><td><b>${rupiah(c.amount)}</b></td><td><span class="status-pill ${c.type === "Pemasukan" ? "status-paid" : "status-pending"}">${c.type}</span></td></tr>`,
    )
    .join("");
}
function render() {
  renderProducts();
  renderSales();
  renderStock();
  renderCash();
  let units = products.reduce((sum, p) => sum + p.stock, 0);
  byId("metric-stock").innerHTML = units + " <small>unit</small>";
  const paid = sales.filter((s) => s.status === "Lunas");
  let revenue = paid.reduce((s, t) => s + t.total, 0),
    profit = paid.reduce((s, t) => s + t.total - t.cost, 0);
  if (paid.length) {
    byId("metric-revenue").textContent = rupiah(revenue);
    byId("metric-profit").textContent = rupiah(profit);
    byId("metric-units").innerHTML = paid.length + " <small>transaksi</small>";
  }
}
// Form bersama untuk menambah transaksi, produk, stok, dan catatan kas.
const backdrop = byId("modal-backdrop");
function field(
  label,
  name,
  type = "text",
  value = "",
  options = null,
  required = true,
  hint = "",
) {
  let input = options
    ? `<select name="${name}" ${required ? "required" : ""}>${options.map((o) => `<option value="${o.value ?? o}">${o.label ?? o}</option>`).join("")}</select>`
    : type === "textarea"
      ? `<textarea name="${name}" ${required ? "required" : ""}>${value}</textarea>`
      : `<input name="${name}" type="${type}" value="${value}" ${required ? "required" : ""} ${type === "number" ? 'min="0"' : ""}>`;
  return `<div class="form-field"><label>${label}</label>${input}${hint ? `<small class="form-hint">${hint}</small>` : ""}</div>`;
}
let mode = "sale",
  editing = null;
function openForm(kind = "sale", data = null) {
  mode = kind;
  editing = data;
  let title = "Buat transaksi",
    fields = "";
  const productOptions = products.map((p) => ({
    value: p.id,
    label: `${p.model} · ${p.capacity} · ${p.color === "Pink" ? "Pink" : "White"} (${p.stock} unit)`,
  }));
  if (kind === "sale") {
    title = "Buat transaksi";
    fields = `<div class="form-grid">${field("Produk dan varian", "product", "", "", productOptions)}${field("Jumlah unit", "qty", "number", "1")} ${field("Metode pembayaran", "method", "", "", ["Tunai", "Transfer", "Kartu", "QRIS"])}${field("Status pembayaran", "status", "", "", ["Lunas", "Menunggu pembayaran"])}${field("Nama karyawan", "employee", "text", "Nadia Rahma")}${field("Catatan", "note", "text", "", null, false)}</div>`;
  } else if (kind === "stock") {
    title = "Catat stok masuk";
    fields = backendMode
      ? `<div class="form-grid">${field("Produk dan varian", "product", "", "", productOptions)}${field("Jumlah unit", "qty", "number", "1")}${field("Harga beli per unit", "cost", "number", "", null, true, "Masukkan harga sesuai nota pembelian pemasok.")}${field("IMEI setiap unit (satu per baris)", "imeis", "textarea", "", null, true, "Masukkan tepat satu IMEI 15 digit untuk setiap unit. Setiap IMEI harus unik.")}${field("Catatan", "note", "text", "", null, false)}</div>`
      : `<div class="form-grid">${field("Produk dan varian", "product", "", "", productOptions)}${field("Jumlah unit", "qty", "number", "1")}${field("Harga beli per unit", "cost", "number", "", null, true, "Masukkan harga sesuai nota pembelian pemasok.")}${field("IMEI unit pertama", "imei", "text", "35824005262600004", null, true, "IMEI demo harus unik untuk setiap unit perangkat.")}${field("Nama karyawan", "employee", "text", "Nadia Rahma")}</div>`;
  } else if (kind === "cash") {
    title = "Catat arus kas";
    fields = `<div class="form-grid">${field("Jenis transaksi", "type", "", "", ["Pemasukan", "Pengeluaran"])}${field("Kategori", "category", "", "", ["Operasional", "Pembelian stok", "Pemasukan lain", "Biaya sewa", "Utilitas", "Lainnya"])}${field("Jumlah (Rp)", "amount", "number", "")}${field("Tanggal", "date", "date", "2026-10-09")}${field("Keterangan", "note", "text", "")}${field("Nama karyawan", "employee", "text", "Nadia Rahma")}</div>`;
  } else if (kind === "editProduct") {
    title = "Ubah data produk";
    fields = backendMode
      ? `<div class="form-grid">${field("Harga jual (Rp)", "price", "number", data.price)}${field("Deskripsi", "description", "textarea", data.description, null, false)}</div>`
      : `<div class="form-grid">${field("Harga jual (Rp)", "price", "number", data.price)}${field("Harga modal/unit (Rp)", "cost", "number", data.cost)}${field("Jumlah stok", "stock", "number", data.stock)}${field("Nomor IMEI demo", "imei", "text", data.imei)}${field("Deskripsi", "description", "textarea", data.description)}</div>`;
  } else if (kind === "product") {
    title = "Tambah produk";
    fields = backendMode
      ? `<div class="form-grid">${field("Model", "model", "", "", ["iPhone 14", "iPhone 14 Pro Max", "iPhone 15", "iPhone 15 Pro Max", "iPhone 16", "iPhone 16 Pro Max", "iPhone 17", "iPhone 17 Pro Max", "iPhone 18", "iPhone 18 Pro Max"])}${field("Kapasitas", "capacity", "", "", ["256 GB", "512 GB"])}${field("Warna", "color", "", "", ["White", "Pink"])}${field("Harga jual (Rp)", "price", "number", "")}${field("Deskripsi", "description", "textarea", "Data demonstrasi produk iPhone iStore.", null, false)}<p class="form-hint">Setelah membuat varian, catat unitnya melalui menu Stok masuk.</p></div>`
      : `<div class="form-grid">${field("Model", "model", "", "", ["iPhone 14", "iPhone 14 Pro Max", "iPhone 15", "iPhone 15 Pro Max", "iPhone 16", "iPhone 16 Pro Max", "iPhone 17", "iPhone 17 Pro Max", "iPhone 18", "iPhone 18 Pro Max"])}${field("Kapasitas", "capacity", "", "", ["256 GB", "512 GB"])}${field("Warna", "color", "", "", ["White", "Pink"])}${field("Harga jual (Rp)", "price", "number", "")}${field("Harga modal/unit (Rp)", "cost", "number", "")}${field("Stok", "stock", "number", "1")}${field("IMEI demo", "imei", "text", "35824005262600041")}${field("Deskripsi", "description", "textarea", "Data demo produk iPhone iStore.")}</div>`;
  } else if (kind === "employee") {
    title = "Tambah karyawan";
    fields = `<div class="form-grid">${field("Nama lengkap", "name", "text", "")}${field("Email", "email", "email", "")}${field("Kata sandi sementara", "password", "text", "")}${field("Peran", "role", "", "", ["Karyawan"])}</div>`;
  }
  byId("modal-title").textContent = title;
  byId("modal-fields").innerHTML = fields;
  backdrop.classList.add("open");
  setTimeout(() => backdrop.querySelector("input,select")?.focus(), 30);
}
function closeModal() {
  backdrop.classList.remove("open");
  byId("modal-form").reset();
}
byId("close-modal").addEventListener("click", closeModal);
byId("cancel-modal").addEventListener("click", closeModal);
backdrop.addEventListener("click", (e) => {
  if (e.target === backdrop) closeModal();
});
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") closeModal();
});
// Simpan data formulir dan perbarui persediaan/arus kas yang terkait.
byId("modal-form").addEventListener("submit", async (e) => {
  e.preventDefault();
  const fd = new FormData(e.currentTarget),
    v = Object.fromEntries(fd.entries());
  try {
    if (backendMode && mode === "stock") {
      const imeis = v.imeis
        .split(/[\r\n,]+/)
        .map((imei) => imei.trim())
        .filter(Boolean);
      await apiRequest("stock-entries", {
        method: "POST",
        body: JSON.stringify({
          product_variant_id: Number(v.product),
          quantity: Number(v.qty),
          unit_cost: Number(v.cost),
          imeis,
          note: v.note || null,
        }),
      });
      closeModal();
      await refreshBackendCatalog();
      toast("Stok masuk berhasil disimpan ke database.");
      return;
    }
    if (backendMode && mode === "product") {
      await apiRequest("products", {
        method: "POST",
        body: JSON.stringify({
          name: v.model,
          capacity_gb: Number.parseInt(v.capacity, 10),
          color: v.color,
          selling_price: Number(v.price),
          description: v.description || null,
        }),
      });
      closeModal();
      await refreshBackendCatalog();
      toast("Varian produk tersimpan. Catat unitnya melalui menu Stok masuk.");
      return;
    }
    if (backendMode && mode === "editProduct") {
      await apiRequest(`products/${editing.id}`, {
        method: "PATCH",
        body: JSON.stringify({
          selling_price: Number(v.price),
          description: v.description || null,
        }),
      });
      closeModal();
      await refreshBackendCatalog();
      toast("Produk berhasil diperbarui.");
      return;
    }
  } catch (error) {
    toast(error.message);
    return;
  }

  if (mode === "sale") {
    let p = products.find((x) => x.id === Number(v.product));
    let qty = Number(v.qty);
    if (!p || p.stock < qty) {
      toast("Stok tidak mencukupi untuk transaksi ini.");
      return;
    }
    let total = p.price * qty;
    sales.unshift({
      id: "TRX-" + Date.now().toString().slice(-9),
      product: p.model,
      variant: p.capacity + " · " + p.color,
      time: "Baru saja",
      employee: v.employee,
      method: v.method,
      status: v.status,
      total,
      cost: p.cost * qty,
    });
    if (v.status === "Lunas") p.stock -= qty;
    if (v.status === "Lunas")
      cash.unshift({
        date: "9 Okt 2026",
        note: "Penjualan " + sales[0].id,
        category: "Penjualan",
        employee: v.employee,
        amount: total,
        type: "Pemasukan",
      });
    toast("Transaksi berhasil dicatat.");
  } else if (mode === "stock") {
    let p = products.find((x) => x.id === Number(v.product));
    if (!p) return;
    p.stock += Number(v.qty);
    p.cost = Number(v.cost);
    p.imei = v.imei;
    stocks.unshift({
      id: "STK-" + Date.now().toString().slice(-9),
      product: p.model,
      variant: p.capacity + " · " + p.color,
      imei: v.imei,
      qty: Number(v.qty),
      cost: Number(v.cost),
      date: "9 Okt 2026",
      employee: v.employee,
    });
    cash.unshift({
      date: "9 Okt 2026",
      note: "Pembelian stok " + p.model,
      category: "Pembelian stok",
      employee: v.employee,
      amount: Number(v.cost) * Number(v.qty),
      type: "Pengeluaran",
    });
    toast("Stok masuk dan arus kas diperbarui.");
  } else if (mode === "cash") {
    cash.unshift({
      date: new Intl.DateTimeFormat("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
      }).format(new Date()),
      note: v.note,
      category: v.category,
      employee: v.employee,
      amount: Number(v.amount),
      type: v.type,
    });
    toast("Catatan arus kas tersimpan.");
  } else if (mode === "editProduct") {
    Object.assign(editing, {
      price: Number(v.price),
      cost: Number(v.cost),
      stock: Number(v.stock),
      imei: v.imei,
      description: v.description,
    });
    toast("Data produk berhasil diperbarui.");
  } else if (mode === "product") {
    products.unshift({
      id: Date.now(),
      model: v.model,
      capacity: v.capacity,
      color: v.color,
      price: Number(v.price),
      cost: Number(v.cost),
      stock: Number(v.stock),
      imei: v.imei,
      description: v.description,
      image: "phone",
    });
    toast("Produk baru berhasil ditambahkan.");
  } else if (mode === "employee") {
    toast(
      "Akun demo berhasil disiapkan. Sambungkan backend untuk menyimpan pengguna.",
    );
  }
  save();
  closeModal();
  render();
});
byId("new-sale").addEventListener("click", () => openForm("sale"));
byId("quick-sale").addEventListener("click", () => openForm("sale"));
byId("add-stock").addEventListener("click", () => openForm("stock"));
byId("add-cash").addEventListener("click", () => openForm("cash"));
byId("add-product").addEventListener("click", () => openForm("product"));
byId("add-employee").addEventListener("click", () => openForm("employee"));
byId("product-search").addEventListener("input", renderProducts);
byId("model-filter").addEventListener("change", renderProducts);
byId("capacity-filter").addEventListener("change", renderProducts);
byId("sales-search").addEventListener("input", (e) => {
  const q = e.target.value.toLowerCase();
  byId("sales-table").innerHTML = sales
    .filter((s) => (s.id + s.product + s.variant).toLowerCase().includes(q))
    .map(
      (s) =>
        `<tr><td class="transaction-id">${s.id}</td><td><b>${s.product}</b><br><small>${s.variant}</small></td><td>${s.employee}</td><td>${s.time}</td><td>${s.method}</td><td><span class="status-pill ${s.status === "Lunas" ? "status-paid" : "status-pending"}">${s.status}</span></td><td><b>${rupiah(s.total)}</b></td></tr>`,
    )
    .join("");
});
// Aksi tombol, ekspor CSV, dan reset dataset demo.
function exportCsv() {
  let csv =
    "Produk,Varian,IMEI,Harga jual,Modal,Stok\n" +
    products
      .map((p) =>
        [p.model, p.capacity, p.imei, p.price, p.cost, p.stock].join(","),
      )
      .join("\n");
  let a = document.createElement("a");
  a.href = URL.createObjectURL(
    new Blob(["\ufeff" + csv], { type: "text/csv;charset=utf-8" }),
  );
  a.download = "laporan-istore.csv";
  a.click();
  URL.revokeObjectURL(a.href);
  toast("Laporan berhasil diekspor.");
}
byId("export-button").addEventListener("click", exportCsv);
byId("report-export").addEventListener("click", exportCsv);
byId("help-button").addEventListener("click", () =>
  toast("Panduan demo: pilih menu di sidebar untuk membuka fitur."),
);
byId("filter-button").addEventListener("click", () =>
  toast("Gunakan pilihan model dan kapasitas di samping kolom pencarian."),
);
byId("reset-demo").addEventListener("click", () => {
  if (backendMode) {
    toast("Produk dan stok tersimpan di database, jadi tidak bisa direset dari sini.");
    return;
  }
  localStorage.removeItem("istore-products");
  localStorage.removeItem("istore-sales");
  localStorage.removeItem("istore-stocks");
  localStorage.removeItem("istore-cash");
  products = seedProducts();
  sales = structuredClone(seedSales);
  stocks = structuredClone(seedStock);
  cash = structuredClone(seedCash);
  render();
  toast("Data demo dikembalikan seperti semula.");
});
if (backendMode) {
  if (!isOwner) byId("add-product").hidden = true;
  refreshBackendCatalog()
    .then(() => {
      const initial = location.hash.slice(1);
      if (pageNames[initial]) go(initial);
    })
    .catch((error) => {
      render();
      toast(`Gagal memuat produk/stok: ${error.message}`);
    });
} else {
  render();
  const initial = location.hash.slice(1);
  if (pageNames[initial]) go(initial);
}

