# Setup Azmedia Attendance — XAMPP + Laravel

## 1. Buat Database di phpMyAdmin

1. Buka `http://localhost/phpmyadmin`
2. Klik **New** di sidebar kiri
3. Isi nama database: `azabsensi`
4. Collation: `utf8mb4_unicode_ci`
5. Klik **Create**

> Password root XAMPP default biasanya kosong. Kalau kamu sudah set password,
> sesuaikan `DB_PASSWORD` di `.env`.

---

## 2. Konfigurasi `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=azabsensi
DB_USERNAME=root
DB_PASSWORD=        # kosong jika default XAMPP
```

---

## 3. Setup Google OAuth

### a. Buat Project di Google Cloud Console

1. Buka [https://console.cloud.google.com](https://console.cloud.google.com)
2. Buat project baru → **New Project** → beri nama (misal: *Azmedia Attendance*)
3. Di sidebar, masuk ke **APIs & Services → OAuth consent screen**
   - User Type: **External** → Create
   - Isi App name, User support email, Developer contact
   - Scopes: tambahkan `email`, `profile`, `openid`
   - Simpan & lanjutkan

### b. Buat OAuth Credentials

1. Masuk ke **APIs & Services → Credentials**
2. Klik **+ Create Credentials → OAuth client ID**
3. Application type: **Web application**
4. Authorized redirect URIs: `http://localhost:8000/auth/google/callback`
5. Klik **Create** → salin **Client ID** dan **Client Secret**

### c. Isi `.env`

```env
GOOGLE_CLIENT_ID=123456789-xxxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-xxxx
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

> Untuk production, ganti `localhost:8000` dengan domain asli dan tambahkan
> ke **Authorized redirect URIs** di Google Console.

---

## 4. Install Dependensi & Migrate

```bash
# Install dependensi PHP (termasuk laravel/socialite yang sudah ditambahkan)
composer install

# Generate app key (jika belum ada)
php artisan key:generate

# Jalankan migrasi (fresh dari awal)
php artisan migrate

# Isi data awal (admin, guru, 2 siswa magang, geofence)
php artisan db:seed
```

---

## 5. Build Frontend

```bash
npm install
npm run build     # production
# atau
npm run dev       # development (watch mode)
```

---

## 6. Storage Link

```bash
php artisan storage:link
```

---

## 7. Jalankan Server

```bash
composer run dev   # jalankan semuanya sekaligus (server + vite + queue)
# atau manual:
php artisan serve
```

Buka `http://localhost:8000`

---

## Akun Default (dari Seeder)

Karena login hanya via Google OAuth, akun seeder ini digunakan untuk
pengembangan / testing manual via `php artisan tinker`.

| Role  | Email                                    | Role default |
|-------|------------------------------------------|--------------|
| Admin | admin@azmedia.id                         | admin        |
| Guru  | guru@azmedia.id                          | guru         |
| Siswa | ahmad.fauzi@student.smkn1jkt.sch.id      | siswa        |
| Siswa | siti.aminah@student.smkn2bdg.sch.id      | siswa        |

**Cara assign role ke user Google baru via Tinker:**
```bash
php artisan tinker
>>> \App\Models\User::where('email', 'email@domain.com')->update(['role' => 'guru'])
```

---

## Struktur Database

```
users         — semua pengguna (admin/guru/siswa), kolom magang ada di sini
geofences     — koordinat & radius area absensi
attendances   — rekap absen harian per user
permissions   — pengajuan izin siswa + approval guru
```

### Relasi

```
users ──< attendances   (one-to-many)
users ──< permissions   (one-to-many, sebagai pengaju)
users ──< permissions   (one-to-many, sebagai approver)
geofences               (standalone, satu aktif sekaligus)
```

---

## URL Penting

| Halaman               | URL                    | Role      |
|-----------------------|------------------------|-----------|
| Login (Google)        | `/login`               | semua     |
| Dashboard Siswa       | `/dashboard`           | siswa     |
| Dashboard Guru        | `/dashboard`           | guru      |
| Form Izin             | `/permissions/create`  | siswa     |
| Riwayat Izin          | `/permissions`         | siswa     |
| Pengaturan Geofence   | `/admin/geofence`      | admin     |
