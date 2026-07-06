# AGENTS.md

- Repo: PHP game shop (`index.php` → landing, `dashboard_user.php`, `admin/admin_dashboard.php`, `login/`, `transaksi/`).
- App is experimental for school; keep changes small and avoid broad refactors.
- `koneksi.php` is the DB source of truth, but it currently opens **PostgreSQL via PDO** (`pgsql:host=...`) while several pages still use **`mysqli` APIs** (`bind_param`, `get_result`, `num_rows`, `insert_id`). Verify DB API before editing related code.
- Admin login path is hardcoded in `login/login.php`: `admin` / `admin123`.
- User credential notes live in `USER DAN ADMIN.txt`; do not treat them as secure secrets.
- No build system/manifests found. Use direct PHP entrypoints, not npm/pnpm workflows.
- Static assets live under `css/`, `java/`, `aset/`, and `admin/css|js/`.
- When changing auth, orders, or admin flows, check `login/login.php`, `admin/admin_dashboard.php`, and `transaksi/beli.php` together.
- Prefer existing file patterns; avoid adding abstractions unless the repo already uses them.
