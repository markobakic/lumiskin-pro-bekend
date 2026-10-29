# Lumiskin Pro — Backend (Laravel + PostgreSQL)

API backend for the Lumiskin Pro Next.js site: products CRUD, blog CRUD,
newsletter signups with discount codes, and an admin auth layer.

- **Local dev:** Laravel Sail (Docker + PostgreSQL)
- **Production:** Virtualmin VPS (Apache/Nginx + PHP-FPM + PostgreSQL)

---

## Local development (Sail)

Requires Docker Desktop running.

```bash
# first run
cp .env.example .env            # (already present; edit if needed)
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

API is served at `http://localhost`. Point the Next.js site at it with
`NEXT_PUBLIC_API_URL=http://localhost` in `lumiskin-pro/.env.local`.

Seeded admin login: **ksenija / Ksenija.123** (change it from `/admin`).

Useful:
```bash
./vendor/bin/sail artisan migrate:fresh --seed   # reset DB + reseed
./vendor/bin/sail down                           # stop
```

---

## Production deploy (Virtualmin)

### 1. Create the virtual server + database
- In Virtualmin, create the virtual server (e.g. `api.lumiskinpro.rs`).
- **Edit Databases → PostgreSQL**: create a database and user, note the credentials.
- Enable PHP-FPM 8.2+ for the domain (Server Configuration → PHP Options).

### 2. Point the web root at `public/`
Laravel must be served from its `public/` directory. Either:
- Set the domain's **Website Documents** directory to `.../public`, or
- Upload the app one level above `public_html` and symlink/replace `public_html` → `public`.

`public/.htaccess` (shipped with Laravel) handles the front-controller rewrites on Apache.

### 3. Upload code + install dependencies
```bash
git clone <repo> .            # or upload the project (exclude vendor/, node_modules)
cp .env.production.example .env
# edit .env: APP_URL, FRONTEND_URL, DB_* credentials
composer install --no-dev --optimize-autoloader
php artisan key:generate
```

### 4. Migrate + seed (first deploy only for seed)
```bash
php artisan migrate --force
php artisan db:seed --force      # first deploy only — loads products/posts/admin
```

### 5. Cache config for performance
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

### 6. Permissions
```bash
chmod -R ug+rw storage bootstrap/cache
# ensure the PHP-FPM user (the virtual server owner) owns storage/ & bootstrap/cache/
```

### 7. On each subsequent deploy
```bash
php artisan down
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
php artisan up
```

---

## CORS
`config/cors.php` reads `FRONTEND_URL` (comma-separated origins). Set it to the
site's production domain(s), e.g. `https://lumiskinpro.rs`. No trailing slash.

## Admin credentials
Seeded as `ksenija / Ksenija.123`. Change from the site's `/admin` → Podešavanja,
or reset via tinker:
```bash
php artisan tinker
>>> $u = App\Models\User::where('username','ksenija')->first();
>>> $u->password = 'NewStrongPass'; $u->save();
```
