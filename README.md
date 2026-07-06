# VGEM

VGEM adalah project toko game berbasis PHP untuk tugas sekolah. Aplikasi ini punya landing page, dashboard user, panel admin, login, dan alur transaksi.

## Fitur

- Landing page dengan hero video
- Login user dan admin
- Dashboard pengguna
- Detail game
- Wishlist
- Transaksi pembelian, invoice, dan ulasan
- Panel admin untuk kelola game

## Struktur Project

- `index.php` — landing page
- `login/` — login dan sign up
- `dashboard_user.php` — dashboard pengguna
- `detail_game.php` — detail game
- `wishlist.php` — wishlist
- `profile.php` — profil user
- `transaksi/` — beli, invoice, reviews
- `admin/` — dashboard dan CRUD admin
- `css/`, `java/`, `aset/` — asset frontend
- `json/vgem-project-data.json` — data project

## Teknologi

- PHP
- PDO
- MySQL
- JavaScript
- HTML/CSS

## Menjalankan Project

1. Pastikan web server dan MySQL aktif.
2. Import database ke `vgem_db`.
3. Sesuaikan kredensial di `koneksi.php` bila perlu.
4. Buka `index.php` lewat browser.

## Login Admin

- Username: `admin`
- Password: `admin123`

## Catatan

- `koneksi.php` adalah sumber koneksi database.
- Project ini masih eksperimental, jadi struktur dan fitur dibuat sederhana.
