# Local (Native) Setup - Windows

This guide covers how to run the Hotel Management System locally on Windows without Docker.

Prerequisites
- PHP 8.2+ installed and added to PATH: https://windows.php.net/
- Composer (global) or `composer.phar`: https://getcomposer.org/download/
- Node.js (LTS) and npm: https://nodejs.org/
- MySQL server (8.0+) or local MariaDB
- Redis (optional) or use `QUEUE_CONNECTION=sync`
- Git

Quick Steps
1. Open PowerShell (Run as Administrator if installing system packages).
2. Clone the repo and cd into it.

```powershell
cd e:\hotel\hms-laravel
```

3. Run the pre-check/setup helper (this will attempt to download `composer.phar` if Composer isn't found):

```powershell
.\scripts\setup-windows.ps1
```

4. Install PHP dependencies (if Composer is available):

```powershell
# If composer is global
composer install --no-interaction --prefer-dist

# Or if composer.phar is in project
php composer.phar install --no-interaction --prefer-dist
```

5. Install Node dependencies (if `package.json` exists):

```powershell
npm ci
```

6. Create `.env` from `.env.local` and update DB credentials if necessary:

```powershell
Copy-Item .env.local .env
# Edit .env with your DB user/password etc.
notepad .env
```

7. Generate app key and run migrations + seeders:

```powershell
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
```

8. Run tests:

```powershell
php artisan test
# or use helper
.\scripts\run-tests.ps1
```

9. Start the built-in server:

```powershell
php artisan serve --host=0.0.0.0 --port=8000
```

10. Verify endpoints:

```powershell
curl http://localhost:8000/api/health
curl http://localhost:8000/api/dashboard/statistics -H "Authorization: Bearer <token>"
```

Troubleshooting
- If `php` is not found: verify PHP installation and add to PATH. Consider installing via WSL2 if preferred.
- If MySQL connection fails: check the `.env` DB_HOST/DB_PORT/DB_DATABASE/DB_USERNAME/DB_PASSWORD.
- If Redis is not available, set `QUEUE_CONNECTION=sync` in `.env` for local testing.
- For missing PHP extensions, install them (pdo_mysql, mbstring, xml, gd, bcmath).

Useful Commands
- Refresh caches:

```powershell
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

- Run migrations only:

```powershell
php artisan migrate
```

- Rollback migrations:

```powershell
php artisan migrate:rollback
```

Notes
- This project is fully Docker-ready too, but this guide focuses on a native Windows setup.
- For production installs prefer Docker or managed cloud services.

