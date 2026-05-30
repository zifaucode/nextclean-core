# NextClean Server Infrastructure

Repositori ini berisi infrastruktur server (VPS) berbasis **Docker Compose** yang dirancang khusus untuk aplikasi **Laravel SaaS (Software as a Service)** level produksi.

## 🚀 Fitur Utama
- **Reverse Proxy (Nginx)**: Mengatur routing traffic global dan pembagian port secara aman.
- **Auto SSL (Certbot Let's Encrypt)**: Konfigurasi keamanan HTTPS hijau otomatis.
- **App Server (PHP 8.3 FPM + Nginx)**: Pemisahan web server statis dan pemroses PHP dinamis untuk performa tinggi.
- **PostgreSQL 15**: Database berstandar ACID yang kokoh, sangat direkomendasikan untuk skema multi-tenant SaaS.
- **Redis**: Penyimpanan *in-memory* super cepat untuk fitur Caching, Session, dan Queue Laravel.
- **Queue Worker & Cron Scheduler**: Container khusus (terdedikasi) untuk memproses tugas antrean di latar belakang (seperti kirim email massal) dan task scheduling harian secara konstan.

---

## 📂 Struktur Direktori

```text
nextclean-infra/
├── install.sh                       # Script instalasi 1-klik (Otomasi Setup & Deploy)
├── nginx-proxy/                     # Environment Global Reverse Proxy
│   ├── docker-compose.yml           # Konfigurasi container Proxy & Certbot
│   └── conf.d/
│       └── default.conf             # Aturan routing domain & Let's Encrypt SSL HTTPS
└── nextclean-server/                # Environment Aplikasi Utama
    ├── docker-compose.yml           # Konfigurasi container App, Webserver, DB, Redis, Worker, Cron
    ├── Dockerfile                   # Spesifikasi image PHP-FPM 8.3 & ekstensi Laravel
    ├── php/local.ini                # Konfigurasi kustom PHP (Memory limit, Upload size)
    ├── nginx/conf.d/app.conf        # Aturan server blok internal Nginx Laravel
    └── src/                         # Folder target tempat Source Code Laravel (di-pull via Git)
```

---

## 🛠️ Cara Deploy ke VPS (1-Click Install)

Pastikan VPS Anda sudah ter-install `docker` (dan plugin `docker compose`).

1. **Pindahkan folder ini ke VPS Anda** (Bisa melalui Git Clone, SCP, atau SFTP).
2. **Login ke VPS** Anda melalui terminal SSH.
3. **Masuk ke folder** infra:
   ```bash
   cd nextclean-infra
   ```
4. **Beri izin eksekusi** pada script instalasi:
   ```bash
   chmod +x install.sh
   ```
5. **Jalankan script** instalasi:
   ```bash
   ./install.sh
   ```

Script instalasi akan mengambil alih secara interaktif: 
1. Bertanya URL repositori Laravel Anda.
2. Memandu pengaturan `.env` interaktif.
3. Menciptakan sertifikat SSL.
4. Menyalakan seluruh sistem.
5. Mengeksekusi instalasi Laravel otomatis (`composer install`, `key:generate`, `storage:link`, `migrate --seed`, `optimize`).

---

## 🔑 Kredensial Default Database

Saat mengedit file `.env` Laravel Anda ketika diminta oleh script, **pastikan** Anda menggunakan settingan di bawah ini agar Laravel bisa terhubung ke container PostgreSQL:

- **DB_CONNECTION**=`pgsql`
- **DB_HOST**=`db` *(Sangat Penting: Gunakan nama service 'db', jangan gunakan '127.0.0.1')*
- **DB_PORT**=`5432`
- **DB_DATABASE**=`nextclean`
- **DB_USERNAME**=`nextclean`
- **DB_PASSWORD**=`nextclean@2026##!!`

---

## 🌐 Konfigurasi Domain
Sistem proxy saat ini sudah di-hardcode (dikonfigurasi) menggunakan domain: `next-clean.demo.minimonster.id`.
Jika Anda ingin mengubah nama domain di kemudian hari untuk produksi nyata, Anda **wajib** mengubahnya secara serentak di dua file berikut:
1. `nginx-proxy/conf.d/default.conf` (ganti semua tulisan domain lama)
2. `install.sh` (ubah variabel `DOMAIN="..."` di dalam script)
