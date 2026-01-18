# Hotel Management System (HMS) - Laravel

A comprehensive Hotel Management System built with Laravel 11, MySQL, and Tailwind CSS.

## Features
- **Customer Management** — Guest profiles and stay history
- **Booking System** — Availability calendar and reservations
- **Room Management** — Room types, pricing, and availability
- **Check-In/Check-Out** — Fast front-desk workflows
- **Billing & Payments** — Invoice generation and payment processing
- **Role-Based Access** — Admin, Receptionist, and Staff roles
- **Responsive UI** — Mobile-friendly dashboards

## Technology Stack
- **Backend:** Laravel 11 (latest LTS)
- **Database:** MySQL 8+
- **Frontend:** Blade templates with Tailwind CSS
- **Auth:** Laravel Sanctum
- **Testing:** PHPUnit

## Installation

### Requirements
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js 18+ (for frontend assets)

### Setup Steps

1. **Clone/Extract the project:**
   ```bash
   cd hms-laravel
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Copy environment file:**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

5. **Configure database in `.env`:**
   ```
   DB_DATABASE=hotel_hms
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

6. **Run migrations:**
   ```bash
   php artisan migrate --seed
   ```

7. **Install and build frontend assets:**
   ```bash
   npm install
   npm run dev
   ```

8. **Start the development server:**
   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`

## Default Credentials (Seeded)
- **Admin Email:** admin@hotel.local
- **Password:** password

## Project Structure
```
hms-laravel/
├── app/
│   ├── Models/              # Eloquent models (User, Guest, Room, Reservation, etc.)
│   ├── Controllers/         # HTTP controllers
│   ├── Services/            # Business logic services
│   └── Policies/            # Authorization policies
├── database/
│   ├── migrations/          # Database schema
│   ├── seeders/             # Database seeders
│   └── factories/           # Model factories for testing
├── resources/
│   ├── views/               # Blade templates
│   └── css/                 # Tailwind CSS
├── routes/
│   ├── web.php              # Web routes
│   └── api.php              # API routes
└── tests/                   # PHPUnit tests
```

## Key Database Tables
- `users` — Staff and admin accounts
- `roles` — User roles (Admin, Receptionist, Staff)
- `guests` — Guest profiles
- `rooms` — Room inventory
- `room_types` — Room type definitions
- `reservations` — Bookings
- `payments` — Payment records
- `invoices` — Billing invoices
- `room_images` — Room photos

## API Endpoints
- `POST /api/login` — Guest/staff login
- `GET /api/rooms` — List available rooms
- `POST /api/reservations` — Create booking
- `GET /api/reservations/{id}` — Get reservation details
- `POST /api/check-in` — Check-in guest
- `POST /api/check-out` — Check-out guest
- `POST /api/invoices` — Generate invoice
- `POST /api/payments` — Record payment

## Development Workflow
- Create features in feature branches
- Run tests: `php artisan test`
- Format code: `php artisan pint`
- Build assets: `npm run build` (production)

## Deployment
- Push to production server or Docker container
- Run: `php artisan migrate --force` on production
- Configure HTTPS and secure `.env`
- Set up scheduled tasks for reports: `php artisan schedule:run`

## Security Notes
- Always use HTTPS in production
- Store sensitive data in `.env` (not in code)
- Use Laravel policies for authorization
- Validate all input server-side
- Enable CSRF protection on all forms
- Do not store raw payment card data

## Support & Documentation
Refer to [Laravel Documentation](https://laravel.com/docs) for more details.

---
**Version:** 1.0.0  
**Status:** In Development
