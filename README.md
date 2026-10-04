# Korse API

Backend untuk aplikasi **Korse**, dibuat pakai Laravel. Fungsinya menyediakan data lewat API (course, bab, materi, forum diskusi, pencapaian) yang nanti dipakai aplikasi Flutter Korse yang sudah ada di repo terpisah.

> **Status saat ini:** baru sebatas database + API. **Belum disambungkan ke Flutter.** API baru bisa dites lewat browser atau Postman.

---

## Daftar isi

1. [Yang dibutuhkan sebelum mulai](#yang-dibutuhkan-sebelum-mulai)
2. [Langkah setelah clone](#langkah-setelah-clone)
3. [Struktur folder project](#struktur-folder-project)
4. [Skema database](#skema-database)
5. [Model dan relasinya](#model-dan-relasinya)
6. [Daftar endpoint API](#daftar-endpoint-api)
7. [Error yang mungkin muncul dan cara memperbaikinya](#error-yang-mungkin-muncul-dan-cara-memperbaikinya)
8. [Alur kerja kalau mau mengubah struktur tabel](#alur-kerja-kalau-mau-mengubah-struktur-tabel)
9. [Yang belum dikerjakan](#yang-belum-dikerjakan)

---

## Yang dibutuhkan sebelum mulai

- **Laragon** (sudah termasuk PHP, MySQL, Composer dalam satu paket) — download di laragon.org, pilih versi **Full**, bukan Lite.
- Setelah install, buka Laragon dan klik **Start All**. Pastikan MySQL dan Apache menyala (ikon hijau) sebelum lanjut.
- Cek Composer sudah ada: buka terminal Laragon (klik kanan jendela Laragon → **Terminal**), ketik `composer --version`. Kalau muncul nomor versi, aman.

## Langkah setelah clone

Jalankan semua perintah di bawah dari terminal, posisi di dalam folder project (`korse-api`).

```bash
cd korse-api
```

### 1. Install dependency PHP

```bash
composer install
```

Folder `vendor/` sengaja **tidak** ikut ter-upload ke GitHub (isinya besar dan bisa di-download ulang kapan saja), jadi langkah ini wajib di setiap laptop, dan agak memakan waktu tergantung koneksi internet.

### 2. Bikin file `.env`

File `.env` juga sengaja tidak ikut ter-upload, karena isinya kredensial yang beda-beda tiap laptop (password database, dll).

```bash
copy .env.example .env
```

Kalau pakai Mac/Linux: `cp .env.example .env`.

### 3. Generate application key

```bash
php artisan key:generate
```

Perintah ini mengisi `APP_KEY` di `.env`, dipakai Laravel untuk enkripsi data sensitif (misalnya sesi login). Tiap laptop punya key sendiri.

### 4. Bikin database MySQL

1. Buka Laragon, klik menu **Database** (atau buka HeidiSQL lewat Laragon).
2. Klik kanan pada koneksi yang muncul → **Create new** → **Database**.
3. Nama database: **`korse`**
4. Collation: pilih yang ada `utf8mb4` di depannya, misalnya `utf8mb4_unicode_ci`.

### 5. Sesuaikan koneksi database di `.env`

Buka file `.env`, cari bagian koneksi database. **Laravel versi baru defaultnya memakai SQLite**, jadi bagian ini harus diganti manual jadi:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=korse
DB_USERNAME=root
DB_PASSWORD=
```

Default Laragon biasanya user `root` tanpa password. Kalau di laptopmu beda (misalnya kamu pernah set password sendiri), sesuaikan `DB_PASSWORD`.

### 6. Jalankan migration dan seeder

```bash
php artisan migrate:fresh --seed
```

Perintah ini membuat ulang semua tabel dari nol, lalu langsung mengisinya dengan data contoh: 2 course (salah satunya "Dasar Statistika" lengkap dengan bab, materi, isi materi, dan status progres), 1 user, 1 thread diskusi, dan 1 pencapaian.

### 7. Jalankan server

```bash
php artisan serve
```

Biarkan terminal ini tetap terbuka selama kamu mau mengakses API. Tutup servernya dengan `Ctrl+C` kalau sudah selesai.

### 8. Tes di browser

Buka:
```
http://127.0.0.1:8000/api/courses
```

Harus muncul teks JSON berisi 2 course. Kalau muncul, setup-mu berhasil.

---

## Struktur folder project

Ini bagian-bagian folder Laravel yang **relevan untuk proyek ini** (folder lain adalah bawaan Laravel, tidak perlu disentuh):

```
korse-api/
├── app/
│   ├── Http/Controllers/Api/   ← logika tiap endpoint API
│   │   ├── CourseController.php
│   │   ├── ChapterController.php
│   │   ├── MaterialController.php
│   │   ├── ThreadController.php
│   │   └── AchievementController.php
│   └── Models/                 ← definisi tabel sebagai class PHP
│       ├── User.php            (bawaan Laravel, sudah dimodifikasi)
│       ├── Course.php
│       ├── Chapter.php
│       ├── MaterialItem.php
│       ├── ContentBlock.php
│       ├── UserProgress.php
│       ├── Thread.php
│       ├── Reply.php
│       └── Achievement.php
├── database/
│   ├── migrations/             ← struktur tabel, dijalankan urut sesuai nama file
│   └── seeders/
│       ├── UserSeeder.php      ← bikin 1 user contoh
│       └── KorseSeeder.php     ← bikin semua data contoh (course, bab, materi, dst)
├── routes/
│   └── api.php                 ← semua endpoint API didaftarkan di sini
├── config/
│   └── cors.php                ← aturan siapa boleh akses API ini
├── .env                        ← TIDAK ikut di-upload, dibuat manual tiap laptop
└── .env.example                ← contoh isi .env, ikut di-upload
```

---

## Skema database

### `users`
*(tabel bawaan Laravel, sudah ditambah 2 kolom)*

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, auto increment | |
| name | string | |
| email | string, unik | |
| password | string | di-hash |
| plan_label | string, nullable | contoh: "Learner Pro Aktif" |
| avatar_url | string, nullable | |

### `courses`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, auto increment | |
| title | string | |
| description | text, nullable | |
| level_label | string, nullable | contoh: "Pemula" |
| cover_image_url | string, nullable | |

### `chapters`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, auto increment | |
| course_id | foreign key → `courses.id` | kalau course dihapus, bab ikut terhapus |
| title | string | |
| headline | string, nullable | |
| module_label | string, nullable | contoh: "MODUL 2 · DASAR STATISTIKA" |
| estimate_label | string, nullable | |
| order | integer, default 0 | urutan tampil |

### `materials`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, auto increment | |
| chapter_id | foreign key → `chapters.id` | |
| title | string | |
| reading_time_label | string, nullable | |
| core_concept_note | text, nullable | |
| order | integer, default 0 | |

**Catatan:** status materi (completed/in_progress/locked) **bukan kolom di sini**, tapi dihitung dari tabel `user_progress`.

### `content_blocks`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, auto increment | |
| material_id | foreign key → `materials.id` | |
| type | enum: `paragraph`, `illustration`, `callout` | |
| callout_type | enum: `definisi`, `contoh`, `tips`, nullable | diisi hanya kalau type = callout |
| text_content | text, nullable | isi paragraf, caption gambar, atau isi callout |
| image_url | string, nullable | diisi hanya kalau type = illustration |
| order | integer, default 0 | |

### `user_progress`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, auto increment | |
| user_id | foreign key → `users.id` | |
| material_id | foreign key → `materials.id` | |
| status | enum: `completed`, `in_progress`, `locked` | |
| completed_at | timestamp, nullable | |

Kombinasi `user_id` + `material_id` dibuat **unik**, jadi satu user tidak bisa punya dua baris progres untuk materi yang sama.

### `threads`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, auto increment | |
| course_id | foreign key → `courses.id` | |
| user_id | foreign key → `users.id` | pembuat thread |
| title | string | |
| body | text | |

### `replies`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, auto increment | |
| thread_id | foreign key → `threads.id` | |
| user_id | foreign key → `users.id` | |
| body | text | |
| author_role | string, nullable | |

### `achievements`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, auto increment | |
| user_id | foreign key → `users.id` | |
| course_id | foreign key → `courses.id`, nullable | |
| title | string | |
| subtitle | string, nullable | |
| status_label | string, nullable | |
| earned_at | timestamp, nullable | |

### Diagram relasi (ringkas)

```
users ──┬──< user_progress >── materials ──< content_blocks
        │                          ^
        │                          |
        ├──< threads >── courses ──┴──< chapters
        │       |
        │       └──< replies
        │
        └──< achievements >── courses
```

Tanda `──<` artinya "satu ke banyak" (satu course punya banyak bab, dst).

---

## Model dan relasinya

Semua model ada di `app/Models/`. Fungsi tiap model:

| Model | Fungsi relasi yang tersedia |
|---|---|
| `Course` | `chapters()`, `threads()` |
| `Chapter` | `course()`, `materials()` |
| `MaterialItem` | `chapter()`, `contentBlocks()`, `progress()` |
| `ContentBlock` | `material()` |
| `UserProgress` | `user()`, `material()` |
| `Thread` | `course()`, `user()`, `replies()` |
| `Reply` | `thread()`, `user()` |
| `Achievement` | `user()`, `course()` |

**Penting soal `MaterialItem`:** model ini punya atribut tambahan bernama `status`, yang **tidak ada sebagai kolom asli** di tabel `materials`. Nilainya dihitung otomatis lewat fungsi `getStatusAttribute()`, yang membaca dari relasi `progress()`. Relasi ini saat ini **di-hardcode ke `user_id = 1`**, karena belum ada sistem login. Jadi semua orang yang akses API ini akan melihat progres milik user id 1 (user "Irul" dari seeder).

---

## Daftar endpoint API

Semua endpoint diawali `http://127.0.0.1:8000/api`.

### `GET /courses`
Mengembalikan semua course, lengkap dengan bab, materi, isi materi, dan status progres di dalamnya (nested).

Contoh potongan respons:
```json
[
  {
    "id": 1,
    "title": "Dasar Statistika",
    "description": "Pengantar konsep statistika untuk pemula...",
    "level_label": "Pemula",
    "cover_image_url": "assets/images/cover_statistika.png",
    "chapters": [
      {
        "id": 2,
        "title": "Distribusi Data",
        "headline": "Kurva, Pola, dan Dispersi",
        "materials": [
          {
            "id": 2,
            "title": "Distribusi Normal",
            "status": "in_progress",
            "content_blocks": [
              { "type": "illustration", "image_url": "...", "text_content": "Gambar 2.1..." },
              { "type": "paragraph", "text_content": "Distribusi normal adalah..." }
            ]
          }
        ]
      }
    ]
  }
]
```

### `GET /courses/{id}`
Satu course saja, bentuk sama seperti di atas.

### `GET /chapters/{id}`
Satu bab beserta materinya (tanpa data course induknya).

### `GET /materials/{id}`
Satu materi, beserta isi kontennya (`content_blocks`) dan data bab induknya (`chapter`), untuk menghitung posisi materi (misalnya "2 dari 4").

### `GET /threads?course_id={id}`
Daftar thread diskusi untuk satu course. Parameter `course_id` sifatnya opsional; kalau tidak diisi, mengembalikan semua thread dari semua course.

### `GET /threads/{id}`
Satu thread, lengkap dengan daftar balasannya (`replies`).

### `GET /achievements?user_id={id}`
Daftar pencapaian satu user. Parameter `user_id` opsional, sama seperti di atas.

---

## Error yang mungkin muncul dan cara memperbaikinya

Ini error-error yang kami temui sendiri waktu setup pertama kali, siapa tahu kejadian lagi.

**"Nothing to migrate" padahal database masih kosong**
Penyebabnya Laravel masih mengarah ke SQLite (bawaan default Laravel versi baru), bukan ke MySQL `korse`. Cek `.env`, pastikan `DB_CONNECTION=mysql`. Kalau ada file `database/database.sqlite`, boleh dihapus setelah `DB_CONNECTION` diperbaiki. Lalu jalankan:
```bash
php artisan config:clear
php artisan migrate:fresh
```

**Error migration karena tabel `users` sudah ada / dobel**
Laravel otomatis sudah menyediakan migration untuk `users` sejak project dibuat (nama file diawali `0001_01_01`). Jangan bikin migration baru untuk `users`; kalau mau tambah kolom, edit migration bawaan itu langsung.

**Folder `routes/api.php` tidak ada**
Laravel versi terbaru tidak otomatis menyediakannya. Jalankan:
```bash
php artisan install:api
```

**File `config/cors.php` tidak ada**
Juga tidak otomatis ada di Laravel versi terbaru. Jalankan:
```bash
php artisan config:publish cors
```
Lalu pastikan `'allowed_origins' => ['*']`.

**`composer: command not found`**
Composer belum ter-install atau belum masuk PATH. Download dari getcomposer.org, install, lalu restart Laragon.

---

## Alur kerja kalau mau mengubah struktur tabel

**Jangan edit tabel langsung lewat HeidiSQL.** Semua perubahan struktur tabel (nambah kolom, dst) harus lewat migration baru:

```bash
php artisan make:migration tambah_kolom_x_ke_tabel_y
```

Isi file migration yang dibuat, lalu jalankan `php artisan migrate`. Setelah itu commit dan push file migration-nya lewat GitHub Desktop. Anggota lain tinggal `git pull`, lalu jalankan `php artisan migrate` di laptop masing-masing untuk menyamakan struktur database mereka.

---

## Yang belum dikerjakan

- **Belum disambungkan ke Flutter.** Project Flutter Korse masih memakai data contoh sendiri (`DummyData.dart`), belum memanggil API ini.
- **Belum ada login/autentikasi.** Semua progres memakai user id 1 secara tetap.
- **Course belum punya progres gabungan** (persen course secara keseluruhan), baru progres per materi.
- **Tombol/aksi untuk mengubah data (bikin thread baru, tandai materi selesai, dst) belum ada endpoint-nya.** Semua endpoint yang ada sekarang baru untuk membaca data (`GET`), belum ada untuk menulis (`POST`/`PUT`).
