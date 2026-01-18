# Run PHPUnit tests via artisan (Windows helper)
Push-Location -Path (Split-Path -Parent $MyInvocation.MyCommand.Definition)

if (Get-Command php -ErrorAction SilentlyContinue) {
    php artisan test
} else {
    Write-Host "PHP not found in PATH. Install PHP 8.2+ and ensure php is available." -ForegroundColor Red
}

Pop-Location
