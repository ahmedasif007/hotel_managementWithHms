SETUP_INSTRUCTIONS.md
======================

## Laravel Hotel Management System - Setup Guide

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js 18+ (for frontend assets)
- Git

### Step 1: Install PHP Dependencies
```bash
cd hms-laravel
composer install
```

### Step 2: Create Environment File
```bash
cp .env.example .env
```

### Step 3: Generate Application Key
```bash
php artisan key:generate
```

### Step 4: Database Configuration
Edit `.env` and update database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotel_hms
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database:
```bash
mysql -u root -p -e "CREATE DATABASE hotel_hms;"
```

### Step 5: Run Migrations and Seeders
```bash
php artisan migrate --seed
```

This will create all tables and seed sample data (roles, users, room types, rooms).

### Step 6: Install Frontend Dependencies
```bash
npm install
```

### Step 7: Build Frontend Assets
```bash
npm run dev
```

For production:
```bash
npm run build
```

### Step 8: Start Development Server
```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

### Default Test Credentials
- **Admin:**
  - Email: admin@hotel.local
  - Password: password
  
- **Receptionist:**
  - Email: receptionist@hotel.local
  - Password: password
  
- **Staff:**
  - Email: staff@hotel.local
  - Password: password

### Running Tests
```bash
php artisan test
```

### File Structure
```
hms-laravel/
├── app/
│   ├── Models/          # Eloquent models
│   ├── Controllers/     # HTTP controllers
│   ├── Services/        # Business logic
│   └── Policies/        # Authorization
├── database/
│   ├── migrations/      # Database schema
│   ├── seeders/         # Sample data
│   └── factories/       # Model factories
├── resources/
│   ├── views/           # Blade templates
│   └── css/             # Tailwind CSS
├── routes/
│   ├── api.php          # API routes
│   └── web.php          # Web routes
└── tests/               # PHPUnit tests
```

### Available API Endpoints

**Authentication:**
- POST `/api/login` — Login with email/password
- POST `/api/logout` — Logout (requires auth)
- GET `/api/me` — Get current user (requires auth)

**Rooms:**
- GET `/api/rooms` — List all rooms
- POST `/api/rooms` — Create room
- GET `/api/rooms/{id}` — Get room details
- PUT `/api/rooms/{id}` — Update room
- DELETE `/api/rooms/{id}` — Delete room
- GET `/api/rooms/availability?check_in_date=YYYY-MM-DD&check_out_date=YYYY-MM-DD` — Check availability

**Guests:**
- GET `/api/guests` — List all guests
- POST `/api/guests` — Register guest
- GET `/api/guests/{id}` — Get guest details
- PUT `/api/guests/{id}` — Update guest
- DELETE `/api/guests/{id}` — Delete guest

**Reservations:**
- GET `/api/reservations` — List all reservations
- POST `/api/reservations` — Create reservation
- GET `/api/reservations/{id}` — Get reservation details
- PUT `/api/reservations/{id}` — Update reservation
- POST `/api/reservations/{id}/check-in` — Check in guest
- POST `/api/reservations/{id}/check-out` — Check out guest
- POST `/api/reservations/{id}/cancel` — Cancel reservation

**Billing:**
- POST `/api/invoices/create/{reservationId}` — Generate invoice
- GET `/api/invoices` — List invoices
- GET `/api/invoices/{id}` — Get invoice details
- POST `/api/payments` — Record payment

### Troubleshooting

**Database Connection Error:**
- Ensure MySQL is running
- Verify credentials in `.env`
- Check database name matches

**Migration Errors:**
- Clear cache: `php artisan cache:clear`
- Reset migrations: `php artisan migrate:reset`
- Re-run: `php artisan migrate --seed`

**Asset Issues:**
- Clear Vite cache: `rm -rf node_modules/.vite`
- Rebuild: `npm run dev`

### Next Steps
1. Test the API with Postman or similar tool
2. Create additional rooms and guests
3. Test booking workflows
4. Implement payment gateway integration
5. Add email notifications
6. Deploy to production server

For more information, refer to [Laravel Documentation](https://laravel.com/docs).
