#!/bin/bash

# Exit on any error
set -e

# Warna untuk output text
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}==============================================${NC}"
echo -e "${GREEN}   Instalasi Otomatis NextClean Server        ${NC}"
echo -e "${GREEN}==============================================${NC}\n"

# 1. Membuat Docker Network
echo -e "${YELLOW}[1/6] Menyiapkan jaringan Docker...${NC}"
docker network inspect nextclean_shared_network >/dev/null 2>&1 || \
    docker network create nextclean_shared_network

# 2. Menjalankan Global Proxy & Setup SSL
echo -e "${YELLOW}[2/6] Menyiapkan SSL dan Menyalakan Nginx Global Proxy...${NC}"

DOMAIN="next-clean.demo.minimonster.id"
mkdir -p nginx-proxy/certbot/conf/live/$DOMAIN
mkdir -p nginx-proxy/certbot/www

# Generate self-signed cert sementara agar Nginx bisa booting awal (karena butuh file pem)
if [ ! -f "nginx-proxy/certbot/conf/live/$DOMAIN/fullchain.pem" ]; then
    echo "Membuat sertifikat sementara..."
    openssl req -x509 -nodes -newkey rsa:2048 -days 1 \
      -keyout "nginx-proxy/certbot/conf/live/$DOMAIN/privkey.pem" \
      -out "nginx-proxy/certbot/conf/live/$DOMAIN/fullchain.pem" \
      -subj "/CN=$DOMAIN"
fi

cd nginx-proxy
docker compose up -d
cd ..

echo -e "\n${YELLOW}--- PROSES SSL (Let's Encrypt) ---${NC}"
read -p "Apakah domain $DOMAIN sudah di-pointing ke IP VPS ini? (y/n): " DNS_READY
if [ "$DNS_READY" == "y" ] || [ "$DNS_READY" == "Y" ]; then
    echo "Meminta SSL Resmi Let's Encrypt..."
    # Hapus cert sementara
    docker exec nextclean-certbot rm -rf /etc/letsencrypt/live/$DOMAIN
    docker exec nextclean-certbot rm -rf /etc/letsencrypt/archive/$DOMAIN
    docker exec nextclean-certbot rm -rf /etc/letsencrypt/renewal/$DOMAIN.conf
    
    docker exec nextclean-certbot certbot certonly --webroot -w /var/www/certbot \
        -d $DOMAIN --email admin@$DOMAIN --rsa-key-size 4096 --agree-tos --force-renewal --non-interactive
    
    echo "Reloading Nginx Proxy..."
    docker exec nextclean-proxy nginx -s reload
else
    echo "DNS belum siap. Server akan menggunakan SSL sementara (Self-Signed)."
fi
echo -e "${YELLOW}----------------------------------${NC}\n"

# 3. Download / Pull Repository
echo -e "${YELLOW}[3/6] Mengunduh Source Code...${NC}"
cd nextclean-server
if [ ! -d "src/.git" ]; then
    echo -e "Folder src belum berupa repositori Git."
    read -p "Masukkan URL Git Repository NextClean Anda (contoh: https://github.com/user/repo.git): " GIT_URL
    if [ -z "$GIT_URL" ]; then
        echo -e "${RED}URL Git tidak boleh kosong. Proses dibatalkan.${NC}"
        exit 1
    fi
    
    # Hapus file placeholder (README.md dsb) agar git clone bisa masuk ke folder kosong
    rm -rf src
    
    # Melakukan Clone langsung dengan nama folder 'src'
    git clone $GIT_URL src
else
    echo "Repository Git ditemukan. Memperbarui source code (git pull)..."
    cd src
    git pull
    cd ..
fi

# 4. Konfigurasi Lingkungan (Env)
echo -e "${YELLOW}[4/6] Menyiapkan konfigurasi .env...${NC}"
if [ ! -f "src/.env" ]; then
    if [ -f "src/.env.example" ]; then
        cp src/.env.example src/.env
        echo -e "File ${GREEN}.env${NC} berhasil digandakan dari .env.example."
        echo -e "\n${RED}PERHATIAN SANGAT PENTING!${NC}"
        echo -e "Tolong buka tab terminal baru, edit file ${YELLOW}nextclean-server/src/.env${NC}"
        echo -e "Sesuaikan minimal konfigurasi berikut agar koneksi database berhasil:"
        echo " - DB_CONNECTION=pgsql"
        echo " - DB_HOST=db"
        echo " - DB_PORT=5432"
        echo " - DB_DATABASE=nextclean"
        echo " - DB_USERNAME=nextclean"
        echo " - DB_PASSWORD=nextclean@2026##!!"
        echo -e "\nTekan [ENTER] jika Anda sudah menyesuaikan isi file .env untuk melanjutkan ke tahap build."
        read
    else
        echo -e "${RED}Peringatan: File .env.example tidak ditemukan di repository! Pastikan framework Anda benar.${NC}"
    fi
else
    echo "File .env sudah ada."
fi

# 5. Build dan Menyalakan Container App + DB
echo -e "${YELLOW}[5/6] Membangun image dan menyalakan server aplikasi...${NC}"
docker compose up -d --build

# 6. Setup Framework Laravel
echo -e "${YELLOW}[6/6] Menjalankan Instalasi Inti Laravel...${NC}"

# Beri jeda 10 detik agar database postgres benar-benar siap menerima koneksi
echo "Menunggu database siap (10 detik)..."
sleep 10

echo ">> Menjalankan composer install..."
docker exec -it nextclean-app composer install --optimize-autoloader --no-dev

echo ">> Generate Application Key..."
docker exec -it nextclean-app php artisan key:generate

echo ">> Mengubungkan storage link..."
docker exec -it nextclean-app php artisan storage:link || true

echo ">> Menjalankan migrasi database dan seeder..."
docker exec -it nextclean-app php artisan migrate --seed --force

echo ">> Optimasi Route, Cache, dan Config untuk kecepatan maskimal..."
docker exec -it nextclean-app php artisan optimize

echo -e "\n${GREEN}==============================================${NC}"
echo -e "${GREEN}   Instalasi Selesai Sempurna!                ${NC}"
echo -e "${GREEN}   Aplikasi Anda kini sudah siap diakses.     ${NC}"
echo -e "${GREEN}==============================================${NC}"
