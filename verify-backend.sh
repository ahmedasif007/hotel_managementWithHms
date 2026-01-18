#!/bin/bash
# Hotel Management System - Backend Verification Script

echo "=== Hotel Management System Backend Verification ==="
echo ""

# Check PHP version
echo "✓ Checking PHP version..."
php -v
echo ""

# Check required directories
echo "✓ Checking project structure..."
directories=(
    "app/Models"
    "app/Http/Controllers"
    "app/Services"
    "app/Providers"
    "app/Policies"
    "config"
    "database/migrations"
    "database/seeders"
    "routes"
    "storage/app"
    "storage/logs"
    "tests"
    "resources/views"
)

for dir in "${directories[@]}"; do
    if [ -d "$dir" ]; then
        echo "  ✓ $dir"
    else
        echo "  ✗ $dir (MISSING)"
    fi
done
echo ""

# Check required files
echo "✓ Checking configuration files..."
files=(
    "composer.json"
    "phpunit.xml"
    "tailwind.config.js"
    "vite.config.js"
    "bootstrap/app.php"
    "routes/api.php"
    "routes/web.php"
)

for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "  ✓ $file"
    else
        echo "  ✗ $file (MISSING)"
    fi
done
echo ""

# Check models
echo "✓ Checking Models..."
models=(
    "app/Models/User.php"
    "app/Models/Role.php"
    "app/Models/Guest.php"
    "app/Models/Room.php"
    "app/Models/RoomType.php"
    "app/Models/Reservation.php"
    "app/Models/Invoice.php"
    "app/Models/Payment.php"
)

for model in "${models[@]}"; do
    if [ -f "$model" ]; then
        echo "  ✓ $(basename $model)"
    else
        echo "  ✗ $(basename $model) (MISSING)"
    fi
done
echo ""

# Check controllers
echo "✓ Checking Controllers..."
controllers=(
    "app/Http/Controllers/AuthController.php"
    "app/Http/Controllers/RoomController.php"
    "app/Http/Controllers/ReservationController.php"
    "app/Http/Controllers/GuestController.php"
    "app/Http/Controllers/BillingController.php"
)

for controller in "${controllers[@]}"; do
    if [ -f "$controller" ]; then
        echo "  ✓ $(basename $controller)"
    else
        echo "  ✗ $(basename $controller) (MISSING)"
    fi
done
echo ""

# Check services
echo "✓ Checking Services..."
services=(
    "app/Services/ReservationService.php"
    "app/Services/BillingService.php"
)

for service in "${services[@]}"; do
    if [ -f "$service" ]; then
        echo "  ✓ $(basename $service)"
    else
        echo "  ✗ $(basename $service) (MISSING)"
    fi
done
echo ""

# Check migrations
echo "✓ Checking Migrations..."
migration_count=$(find database/migrations -name "*.php" 2>/dev/null | wc -l)
echo "  Found $migration_count migrations"
echo ""

# Check seeders
echo "✓ Checking Seeders..."
seeder_count=$(find database/seeders -name "*.php" 2>/dev/null | wc -l)
echo "  Found $seeder_count seeders"
echo ""

echo "=== Verification Complete ==="
echo ""
echo "Next steps:"
echo "1. composer install"
echo "2. cp .env.example .env"
echo "3. php artisan key:generate"
echo "4. Configure database in .env"
echo "5. php artisan migrate --seed"
echo "6. npm install && npm run dev"
echo "7. php artisan serve"
echo ""
