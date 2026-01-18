<#
Project-level automation to run migrations, seeders, tests, and start server.
Run from project root: powershell -ExecutionPolicy Bypass -File .\scripts\setup-project.ps1
#>

param(
    [switch]$migrate,
    [switch]$test,
    [switch]$serve
)

Push-Location -Path (Split-Path -Parent $MyInvocation.MyCommand.Definition)

function Run-Artisan($args) {
    if (Get-Command php -ErrorAction SilentlyContinue) {
        php artisan $args
    } elseif (Test-Path "$PWD\composer.phar") {
        php -d allow_url_fopen=1 $PWD\composer.phar run-script artisan -- $args
    } else {
        Write-Host "PHP not found. Cannot run artisan commands." -ForegroundColor Red
    }
}

if ($migrate) {
    Write-Host "Running migrations and seeders..." -ForegroundColor Cyan
    Run-Artisan "migrate --force"
    Run-Artisan "db:seed --force"
}

if ($test) {
    Write-Host "Running test suite..." -ForegroundColor Cyan
    Run-Artisan "test"
}

if ($serve) {
    Write-Host "Starting local server on http://localhost:8000 ..." -ForegroundColor Cyan
    Run-Artisan "serve --host=0.0.0.0 --port=8000"
} else {
    Write-Host "No action selected. Use -migrate, -test, and/or -serve switches." -ForegroundColor Yellow
}

Pop-Location
