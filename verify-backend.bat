@echo off
REM Hotel Management System - Backend Verification Script for Windows

echo === Hotel Management System Backend Verification ===
echo.

REM Check PHP version
echo Checking PHP version...
php -v
echo.

REM Check required directories
echo Checking project structure...
setlocal enabledelayedexpansion
set "dirs[0]=app\Models"
set "dirs[1]=app\Http\Controllers"
set "dirs[2]=app\Services"
set "dirs[3]=app\Providers"
set "dirs[4]=config"
set "dirs[5]=database\migrations"
set "dirs[6]=database\seeders"
set "dirs[7]=routes"
set "dirs[8]=storage\app"
set "dirs[9]=storage\logs"
set "dirs[10]=tests"
set "dirs[11]=resources\views"

for /L %%i in (0,1,11) do (
    if exist "!dirs[%%i]!" (
        echo   [OK] !dirs[%%i]!
    ) else (
        echo   [MISSING] !dirs[%%i]!
    )
)
echo.

REM Check required files
echo Checking key files...
set "files[0]=composer.json"
set "files[1]=phpunit.xml"
set "files[2]=tailwind.config.js"
set "files[3]=vite.config.js"
set "files[4]=bootstrap\app.php"
set "files[5]=routes\api.php"
set "files[6]=routes\web.php"

for /L %%i in (0,1,6) do (
    if exist "!files[%%i]!" (
        echo   [OK] !files[%%i]!
    ) else (
        echo   [MISSING] !files[%%i]!
    )
)
echo.

echo === Verification Complete ===
echo.
echo Next steps:
echo 1. composer install
echo 2. copy .env.example .env
echo 3. php artisan key:generate
echo 4. Configure database in .env
echo 5. php artisan migrate --seed
echo 6. npm install ^&^& npm run dev
echo 7. php artisan serve
echo.
