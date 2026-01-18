<#
PowerShell helper: Check prerequisites for native Windows development
Run from project root: `.	emplates\scripts\setup-windows.ps1` or `.	ools\setup-windows.ps1` (this file)
#>

# Helper to write colored output
function Write-Ok($msg) { Write-Host "[OK]  $msg" -ForegroundColor Green }
function Write-Warn($msg) { Write-Host "[WARN] $msg" -ForegroundColor Yellow }
function Write-Err($msg) { Write-Host "[ERR]  $msg" -ForegroundColor Red }

Push-Location -Path (Split-Path -Parent $MyInvocation.MyCommand.Definition)

Write-Host "Checking system prerequisites for HMS (native Windows)...`n" -ForegroundColor Cyan

# Check PHP
$php = Get-Command php -ErrorAction SilentlyContinue
if ($php) { Write-Ok "PHP found: $($php.Path)" } else { Write-Warn "PHP not found. Install PHP 8.2+ and ensure it's in PATH. https://windows.php.net/" }

# Check Composer
$composer = Get-Command composer -ErrorAction SilentlyContinue
if ($composer) { Write-Ok "Composer found: $($composer.Path)" } else {
    if ($php) {
        Write-Warn "Composer not found. Will attempt to download composer.phar and create composer.bat for local use."
        $composerInstaller = "https://getcomposer.org/installer"
        try {
            Invoke-WebRequest -Uri $composerInstaller -OutFile "composer-setup.php" -UseBasicParsing
            php composer-setup.php --install-dir="$PWD" --filename=composer.phar
            Remove-Item composer-setup.php -Force
            Write-Ok "composer.phar downloaded. You can run: php composer.phar <command>"
        } catch {
            Write-Err "Failed to download composer. Please install Composer manually: https://getcomposer.org/download/"
        }
    } else {
        Write-Err "Composer installation requires PHP. Install PHP first. https://windows.php.net/"
    }
}

# Check Node / npm
$node = Get-Command node -ErrorAction SilentlyContinue
$npm = Get-Command npm -ErrorAction SilentlyContinue
if ($node -and $npm) { Write-Ok "Node.js and npm available: $($node.Path)" } else { Write-Warn "Node/npm not found. Install Node.js (LTS) from https://nodejs.org/" }

# Check MySQL
$mysql = Get-Command mysql -ErrorAction SilentlyContinue
if ($mysql) { Write-Ok "MySQL client available: $($mysql.Path)" } else { Write-Warn "MySQL client not found. Ensure MySQL server is installed and mysql client is in PATH." }

# Check Redis
$redisCli = Get-Command redis-cli -ErrorAction SilentlyContinue
if ($redisCli) { Write-Ok "redis-cli available: $($redisCli.Path)" } else { Write-Warn "redis-cli not found. Install Redis for Windows or use WSL. https://redis.io/" }

# Project-level installs
Write-Host "`nProject setup steps (will run if prerequisites met):" -ForegroundColor Cyan

# Use composer if available, else php composer.phar
$composerCmd = if (Get-Command composer -ErrorAction SilentlyContinue) { 'composer' } elseif (Test-Path "$PWD\composer.phar") { "php $PWD\composer.phar" } else { $null }

if ($composerCmd) {
    Write-Host "Running: $composerCmd install --no-interaction --prefer-dist" -ForegroundColor Gray
    iex "$composerCmd install --no-interaction --prefer-dist"
} else {
    Write-Warn "Skipping composer install. Install Composer or composer.phar and re-run this script."
}

# NPM install if package.json exists
if (Test-Path "$PWD\package.json") {
    if (Get-Command npm -ErrorAction SilentlyContinue) {
        Write-Host "Running: npm ci" -ForegroundColor Gray
        npm ci
    } else {
        Write-Warn "npm not found. Skip frontend dependency install."
    }
}

# Copy env and generate key
if (-Not (Test-Path "$PWD\.env")) {
    if (Test-Path "$PWD\.env.local") {
        Copy-Item -Path ".env.local" -Destination ".env"
        Write-Ok "Created .env from .env.local"
    } else {
        Write-Warn ".env.local not found. Create a .env file manually based on .env.example"
    }
}

if (Get-Command php -ErrorAction SilentlyContinue) {
    try {
        php artisan key:generate
        Write-Ok "App key generated"
    } catch {
        Write-Warn "Could not run artisan. Ensure dependencies are installed and vendor/bin exists."
    }
}

Write-Host "\nNext steps (manual or automated):" -ForegroundColor Cyan
Write-Host "1) Ensure MySQL & Redis are running and `.env` DB credentials are correct."
Write-Host "2) Run migrations: php artisan migrate --seed" -ForegroundColor Gray
Write-Host "3) Run tests: php artisan test" -ForegroundColor Gray
Write-Host "4) Start local server: php artisan serve --host=0.0.0.0 --port=8000" -ForegroundColor Gray

Pop-Location
