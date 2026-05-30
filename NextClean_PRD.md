# Product Requirements Document (PRD) — NextClean

## 1. Ringkasan Produk

| Detail              | Deskripsi                                                                                          |
| ------------------- | -------------------------------------------------------------------------------------------------- |
| **Nama Produk**     | NextClean                                                                                          |
| **Deskripsi**       | Aplikasi laundry berbasis web yang ringan, cepat, dan mudah digunakan untuk mengelola operasional laundry multi-outlet. |
| **Teknologi Utama** | Laravel, PostgreSQL, Bootstrap 5, DataTables Server-side.                                          |
| **Fitur Unggulan**  | Multi-outlet, Keuangan Akurat, Absensi + Foto & Geolocation (menyediakan API untuk dikonsumsi mobile app), Real-time Monitoring. |

---

## 2. Tujuan & Target Pengguna

### 2.1 Tujuan Utama

- **Efisiensi Multi-Outlet:** Mengelola banyak cabang dalam satu dashboard terpusat.
- **Akurasi Keuangan:** Perhitungan laba rugi otomatis dan pencegahan double entry.
- **Kemudahan Operasional:** Antarmuka kasir yang cepat dan responsif.
- **Monitoring Real-time:** Memudahkan owner memantau performa bisnis kapan saja.
- **Integritas Absensi:** Validasi kehadiran menggunakan Face Tracking & Geolocation.

### 2.2 Target Pengguna

| Role             | Kebutuhan Utama                                                            |
| ---------------- | -------------------------------------------------------------------------- |
| **Super Admin**  | Monitoring seluruh outlet, laporan keuangan konsolidasi, manajemen sistem. |
| **Admin Outlet** | Pengelolaan transaksi, stok, dan operasional harian di outlet spesifik.    |
| **Kasir**        | Input transaksi laundry, cetak nota, update status pengerjaan.             |
| **Supervisor**   | Monitoring pengerjaan dan performa tim di lapangan.                        |
| **Karyawan**     | Absensi mandiri via mobile (akses via API).                                |

---

## 3. Fitur Utama

### 3.1 Dashboard Monitoring

Menampilkan ringkasan bisnis secara real-time dengan filter berdasarkan role:

- **Metrik Utama:** Total transaksi, Pendapatan (Harian/Bulanan), Laundry (Proses/Selesai).
- **Keuangan:** Piutang pelanggan & performa tiap outlet.
- **Visualisasi:** Grafik tren transaksi harian/mingguan.

### 3.2 Manajemen Multi-Outlet

- CRUD Outlet dengan setting spesifik (alamat, jam operasional).

### 3.3 Manajemen Transaksi

Mendukung berbagai jenis layanan laundry:

- **Jenis:** Kiloan, Satuan, Express, Member, Corporate.
- **Alur Kerja:**
    - Input cepat dengan Barcode/Invoice ID.
    - Estimasi selesai otomatis.
    - **Status Pengerjaan:** `Diterima` → `Dicuci` → `Disetrika` → `Selesai` → `Diambil`.
    - Cetak Nota (Thermal Printer Support).

### 3.4 Sistem Keuangan & Akuntansi

- **Pencatatan:** Pemasukan, Pengeluaran Operasional, Gaji Karyawan.
- **Laporan Otomatis:** Laba Rugi, Cash Flow, Rekap Harian/Bulanan/Tahunan.
- **Manajemen Utang/Piutang:** Piutang pelanggan & hutang supplier.
- **Audit:** Log transaksi untuk mencegah manipulasi data.

### 3.5 Absensi & Face Tracking (API Only)

Sistem ini menyediakan endpoint API untuk aplikasi mobile:

- **Fitur API:** Clock In/Out, Face Verification, Geolocation Validation.
- **Keamanan:** Deteksi Fake GPS & Validasi Radius (Geofencing).
- **Rekap:** Menampilkan foto selfie & riwayat absensi di web admin.

---

## 4. Arsitektur & Teknologi

### 4.1 Tech Stack

| Komponen     | Teknologi                              | Catatan                                                              |
| ------------ | -------------------------------------- | -------------------------------------------------------------------- |
| **Backend**  | Laravel (Monolith Modular)             | —                                                                    |
| **Frontend** | Bootstrap 5                            | Responsive Design                                                    |
| **Database** | PostgreSQL                             | Optimized Indexing; relasi data dikelola via aplikasi (tanpa FK DB). |
| **Auth API** | Laravel Sanctum                        | Authentication untuk mobile app.                                     |
| **Tabel UI** | DataTables                             | Server-side Processing.                                              |

### 4.2 Struktur Modul

| No | Modul        | Cakupan                                  |
| -- | ------------ | ---------------------------------------- |
| 1  | `Auth`       | Authentication & Role Management.        |
| 2  | `Outlet`     | Branch Management & Settings.            |
| 3  | `Laundry`    | Transaction & Workflow.                  |
| 4  | `Finance`    | Accounting, Expenses, & Salaries.        |
| 5  | `Attendance` | API Endpoints & Attendance Dashboard.    |
| 6  | `Report`     | Export Engine (PDF / Excel / Print).      |

---

## 5. Struktur Database Utama

Daftar tabel inti yang akan digunakan:

| Tabel                                  | Fungsi                    |
| -------------------------------------- | ------------------------- |
| `users`, `roles`                       | Manajemen akses           |
| `outlets`                              | Data cabang               |
| `employees`, `customers`              | Data entitas              |
| `transactions`, `transaction_details` | Data inti laundry         |
| `expenses`                             | Pengeluaran operasional   |
| `salaries`                             | Penggajian                |
| `attendances`, `face_encodings`       | Data kehadiran            |

---

## 6. Non-Functional Requirements

| Aspek            | Ketentuan                                                         |
| ---------------- | ----------------------------------------------------------------- |
| **Performa**     | Halaman utama load < 2 detik; DataTables responsif > 10.000 row. |
| **Keamanan**     | Role-based access, Sanctum token, validasi geofencing.            |
| **Skalabilitas** | Arsitektur modular; siap dipisah ke microservice jika diperlukan. |
| **Kompatibilitas** | Browser modern (Chrome, Firefox, Edge, Safari terbaru).         |

---

## 7. Ketentuan Teknis

- **Relasi Database:** Tidak menggunakan Foreign Key di level database. Seluruh relasi data dikelola melalui aplikasi (Laravel Eloquent).
- **API Design:** RESTful JSON API untuk seluruh endpoint mobile (Absensi, dll.).
- **Laporan:** Mendukung export ke PDF, Excel, dan cetak langsung (Print).

---

## 8. Prioritas Pengembangan (Roadmap)

### Fase 1 — MVP (Minimum Viable Product)

- Auth & Role Management.
- CRUD Multi-outlet.
- Core Laundry Transaction (Input, Status, Print).
- Dashboard & Keuangan Dasar.
- API Absensi Dasar.

### Fase 2 — Fitur Lanjutan

- Laporan Keuangan Lengkap (Laba Rugi, Cash Flow).
- Manajemen Utang/Piutang.
- Manajemen Gaji Karyawan.
- Face Tracking & Geofencing pada Absensi.

### Fase 3 — Optimasi & Ekspansi

- Grafik & Visualisasi Dashboard Lanjutan.
- Export Laporan (PDF/Excel).
- Optimasi Performa (Caching, Query Tuning).
- Dokumentasi API untuk Integrasi Pihak Ketiga.
