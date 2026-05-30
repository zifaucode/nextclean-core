# Product Requirements Document (PRD)

**Nama Produk:** Nextclean (SaaS Laundry System)
**Versi Dokumen:** 1.0.2
**Tanggal:** 30 Mei 2026
**Author:** Project Manager
**Status:** Draft / Review

---

# 1. Ringkasan Produk

## Latar Belakang

Nextclean adalah platform Software as a Service (SaaS) yang dirancang untuk membantu para pemilik bisnis laundry mengelola operasional mereka secara terpusat.

Sistem ini menggunakan pendekatan **multi-tenant**, di mana Nextclean bertindak sebagai penyedia layanan (**Superadmin**), dan pemilik laundry bertindak sebagai **Subscriber (Admin)**. Setiap Admin akan mendapatkan pengalaman sistem yang disesuaikan dengan branding mereka sendiri (**white-labeling**).

## Tujuan Produk

- Menyediakan sistem manajemen operasional laundry yang dapat digunakan oleh banyak entitas bisnis secara bersamaan tanpa saling tumpang tindih.
- Menghadirkan fitur white-labeling (personalisasi branding) bagi setiap pelanggan SaaS.
- Mengotomatisasi proses onboarding pelanggan baru agar sistem langsung siap digunakan.

## Problem Statement

Pemilik bisnis laundry sering kali kesulitan mengelola banyak cabang, melacak kinerja karyawan (kehadiran dan gaji), serta memantau transaksi secara terpusat karena ketiadaan sistem yang bisa disesuaikan dengan entitas bisnis dan cabang mereka sendiri.

---

# 2. Objectives & Success Metrics

## Objectives

1. Memastikan pemisahan data pelanggan, transaksi, dan operasional antar entitas bisnis (Admin) terjamin 100%.
2. Mengotomatisasi inisialisasi data layanan/produk bagi pelanggan baru melalui duplikasi data template.
3. Memfasilitasi alur operasional dari mulai absensi karyawan (via mobile) hingga pencatatan transaksi kasir secara mulus.

## Success Metrics / KPI

| Metric                      | Target                                        |
| --------------------------- | --------------------------------------------- |
| User Onboarding Time        | < 5 menit untuk Admin baru siap pakai         |
| Data Isolation Leakage      | 0% (Tidak ada data bocor antar bisnis/cabang) |
| Keberhasilan Duplikasi Data | 100% pada saat pendaftaran Admin baru         |

---

# 3. Scope

## In Scope

- Manajemen pendaftaran dan branding Admin (Subscriber) oleh Superadmin.
- Hak akses dan Role Management (Superadmin, Admin, Supervisor, Kasir, Karyawan).
- CRUD Manajemen Master Data oleh Admin (Outlet, Layanan, Pelanggan, Karyawan, User Akun).
- Modul Transaksi POS & Cetak Struk.
- Modul Pengeluaran & Penggajian Karyawan.
- Penerimaan data kehadiran karyawan dari sistem Mobile.

## Out of Scope

- Aplikasi Mobile untuk pelanggan akhir (End-user/Customer App).
- Modul Akuntansi Lanjutan (Buku Besar, Jurnal Umum, Laporan Pajak).
- Desain arsitektur database dan tech-stack (akan didefinisikan di SDD).

---

# 4. User Persona

## Persona 1 — Superadmin (Sistem Base)

| Attribute   | Detail                                                                  |
| ----------- | ----------------------------------------------------------------------- |
| Role        | Pengelola Utama Sistem Nextclean                                        |
| Goals       | Mendaftarkan subscriber baru dan mengatur ketersediaan layanan SaaS     |
| Pain Points | Pembuatan akun klien yang lama jika harus mengatur master data dari nol |

## Persona 2 — Admin (Pemilik Laundry / Subscriber)

| Attribute   | Detail                                                                        |
| ----------- | ----------------------------------------------------------------------------- |
| Role        | Pemilik Bisnis (Pelanggan Nextclean)                                          |
| Goals       | Memantau seluruh cabang, mengatur layanan/produk, dan mengelola gaji karyawan |
| Pain Points | Pencatatan transaksi dan absensi antar cabang sering tidak sinkron            |

## Persona 3 — Kasir

| Attribute   | Detail                                                                  |
| ----------- | ----------------------------------------------------------------------- |
| Role        | Staf Operasional di Cabang/Outlet                                       |
| Goals       | Melayani pelanggan, mencatat transaksi dengan cepat, dan mencetak struk |
| Pain Points | Sistem yang lambat atau membingungkan saat ada antrean pelanggan        |

---

# 5. User Stories

1. Sebagai Superadmin, saya ingin mendaftarkan subscriber baru beserta nama brand mereka (misal: **"Coint Plus"**), agar sistem secara otomatis membuat akun Admin dan mengubah tampilan antarmuka sesuai brand tersebut.

2. Sebagai Superadmin, saya ingin sistem otomatis menduplikasi template layanan/produk ke akun Admin baru, agar mereka tidak perlu menginput layanan standar dari awal.

3. Sebagai Admin, saya ingin membuat dan mengelola outlet (cabang), agar laporan dan operasional tiap lokasi bisa dilacak secara terpisah.

4. Sebagai Admin, saya ingin melihat data kehadiran karyawan yang masuk dari aplikasi mobile, agar saya bisa menghitung gaji secara akurat sesuai data real-time.

5. Sebagai Kasir, saya ingin melihat layanan/produk dan membuat transaksi hanya untuk outlet tempat saya ditugaskan, agar tidak terjadi salah pencatatan ke cabang lain.

---

# 6. Functional Requirements

| ID     | Requirement                                                                                                               | Priority |
| ------ | ------------------------------------------------------------------------------------------------------------------------- | -------- |
| FR-001 | Sistem harus menampilkan Nama Brand yang berbeda pada antarmuka berdasarkan akun Admin yang sedang aktif                  | High     |
| FR-002 | Saat Superadmin membuat Admin baru, sistem harus menyalin data dummy layanan (tanpa outlet) ke kepemilikan Admin tersebut | High     |
| FR-003 | Admin dapat melakukan CRUD pada entitas: Outlet, Layanan, Pelanggan, Karyawan, dan User                                   | High     |
| FR-004 | Sistem dapat menerima dan menyimpan log absensi (Kehadiran) dari aplikasi Mobile karyawan                                 | High     |
| FR-005 | Kasir dapat memproses transaksi laundry dan memicu perintah cetak ke Thermal Printer                                      | High     |
| FR-006 | Gaji karyawan hanya dapat dikalkulasi/dikelola oleh Admin berdasarkan data karyawan dan absensi yang relevan              | Medium   |

---

# 7. Non-Functional Requirements

| Category       | Requirement                                                                                |
| -------------- | ------------------------------------------------------------------------------------------ |
| Keamanan Data  | Harus ada pemisahan hak milik data yang absolut antar Admin (Tenant) dan antar Outlet      |
| Usability      | Antarmuka kasir (POS) harus dirancang untuk proses transaksi yang cepat (minimal klik)     |
| Ketersediaan   | Sistem harus dapat diakses 24/7 untuk mendukung operasional laundry di berbagai zona waktu |
| Kompatibilitas | Modul cetak struk harus kompatibel dengan printer thermal standar pasar                    |

---

# 8. Business Flow Detail

## 8.1 Alur Onboarding Klien Baru (White-labeling)

### Deskripsi

Alur bisnis ketika ada pemilik laundry baru yang berlangganan Nextclean.

### Proses

1. Superadmin menerima pendaftaran dan membuat profil Admin baru di sistem Nextclean.
2. Superadmin memasukkan nama branding klien (contoh: **"Coint Plus"**).
3. Saat data disimpan, sistem di belakang layar menyalin katalog produk/layanan default (cuci kiloan, setrika, dll.) menjadi milik Admin "Coint Plus".
4. Admin "Coint Plus" menerima akses login.
5. Saat Admin "Coint Plus" masuk ke sistem, logo dan nama sistem berubah menjadi "Coint Plus" (bukan lagi Nextclean).

---

# 9. User Flow Transaksi

```text
Login (Kasir)
    ↓
Sistem mendeteksi Outlet Kasir
    ↓
Kasir Memilih Layanan/Produk
(Hanya yang tersedia di Outlet tersebut)
    ↓
Input Data Pelanggan &
Berat/Jumlah Laundry
    ↓
Proses Pembayaran
    ↓
Simpan Transaksi & Cetak Struk
```

---

# 10. Matrix Peran & Hak Akses (RBAC)

| Role       | Batasan Akses Data                                          | Kemampuan Utama                                                    |
| ---------- | ----------------------------------------------------------- | ------------------------------------------------------------------ |
| Superadmin | Seluruh Subscriber / Admin                                  | Membuat akun Admin, menentukan template produk base                |
| Admin      | Seluruh data di dalam entitas bisnisnya saja (semua cabang) | CRUD Outlet, Layanan, Pelanggan, Karyawan, User, Pengeluaran, Gaji |
| Supervisor | Hanya data pada outlet yang ditugaskan kepadanya            | Memantau transaksi dan operasional outlet spesifik                 |
| Kasir      | Hanya data pada outlet yang ditugaskan kepadanya            | Melihat produk outlet, membuat transaksi pelanggan                 |
| Karyawan   | Hanya data personal miliknya                                | Mengirim data kehadiran via mobile                                 |

---

# 11. UI/UX Notes

## Design Principles

### White-label Ready

Komponen visual utama (seperti menu bar, logo).

### Efisiensi POS

Halaman Kasir difokuskan pada kecepatan; form input harus berurutan, jelas, dan mendukung penyelesaian transaksi dalam waktu singkat.

### Format Struk

Tata letak struk harus ringkas, menggunakan teks yang optimal untuk kertas thermal lebar 58mm atau 80mm.

---

# 12. Risks & Constraints

## Risiko Bisnis

### Kesalahan Pemisahan Data

Jika terjadi bug pada akses data, pelanggan bisa melihat data transaksi cabang atau bisnis milik orang lain, yang dapat merusak kepercayaan (trust) pada produk SaaS.

### Kendala Integrasi Perangkat Keras

Kesulitan kasir dalam menghubungkan aplikasi berbasis web dengan printer thermal lokal di berbagai sistem operasi.

## Constraints

Karyawan memerlukan perangkat terpisah (aplikasi mobile) untuk melakukan absensi kehadiran (SUDAH DISIAPKAN APK NYA, hanya tinggal konsum api saja).

---

# 13. Acceptance Criteria (Kriteria Penerimaan)

## Modul Multi-Tenant & Branding

- [ ] Superadmin berhasil menyimpan pendaftaran Admin baru beserta nama brand-nya.
- [ ] Admin baru secara otomatis memiliki data layanan/produk awal yang disalin dari data master Superadmin.
- [ ] Teks/Logo aplikasi berubah sesuai brand Admin saat Admin tersebut login.

## Manajemen Operasional

- [ ] Admin berhasil membuat Outlet baru.
- [ ] Admin berhasil membuat akun Kasir dan menugaskannya ke Outlet tertentu secara spesifik.

## Modul Transaksi

- [ ] Kasir tidak dapat melihat transaksi atau pelanggan dari Outlet yang bukan tempatnya ditugaskan.
- [ ] Kasir berhasil membuat transaksi baru hingga status sukses.
- [ ] Struk bukti transaksi berhasil dihasilkan oleh sistem dengan informasi outlet yang sesuai.

---

**End of Document**
