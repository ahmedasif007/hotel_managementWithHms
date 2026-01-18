# Backend Testing Guide

## Quick Start Testing

### 1. Check Backend Health
```bash
curl http://localhost:8000/api/health
```

Expected Response:
```json
{
    "status": "OK",
    "app": {
        "name": "Hotel Management System",
        "env": "local",
        "debug": true,
        "url": "http://localhost"
    },
    "database": "OK",
    "timestamp": "2026-01-11T10:30:00Z"
}
```

### 2. Detailed Health Check
```bash
curl http://localhost:8000/api/health/detailed
```

This shows:
- PHP version
- Laravel version
- Database status
- Model counts
- System configuration

---

## Authentication Flow

### Step 1: Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@hotel.local",
    "password": "password"
  }'
```

Save the token from response:
```json
{
    "user": { ... },
    "token": "1|abcdef123456"
}
```

### Step 2: Use Token in Requests
```bash
TOKEN="1|abcdef123456"
curl -X GET http://localhost:8000/api/me \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
```

---

## API Testing Workflow

### Test Rooms
```bash
TOKEN="your_token_here"

# List all rooms
curl -X GET http://localhost:8000/api/rooms \
  -H "Authorization: Bearer $TOKEN"

# Check availability
curl -X GET "http://localhost:8000/api/rooms/availability?check_in_date=2026-01-15&check_out_date=2026-01-18" \
  -H "Authorization: Bearer $TOKEN"
```

### Test Guests
```bash
# List guests
curl -X GET http://localhost:8000/api/guests \
  -H "Authorization: Bearer $TOKEN"

# Create guest
curl -X POST http://localhost:8000/api/guests \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Jane",
    "last_name": "Smith",
    "email": "jane@example.com",
    "phone": "555-0200"
  }'
```

### Test Reservations
```bash
# List reservations
curl -X GET http://localhost:8000/api/reservations \
  -H "Authorization: Bearer $TOKEN"

# Create reservation
curl -X POST http://localhost:8000/api/reservations \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "guest_id": 1,
    "room_id": 1,
    "check_in_date": "2026-01-15",
    "check_out_date": "2026-01-18",
    "number_of_guests": 1
  }'

# Check in
curl -X POST http://localhost:8000/api/reservations/1/check-in \
  -H "Authorization: Bearer $TOKEN"

# Check out
curl -X POST http://localhost:8000/api/reservations/1/check-out \
  -H "Authorization: Bearer $TOKEN"
```

### Test Billing
```bash
# Create invoice
curl -X POST http://localhost:8000/api/invoices/create/1 \
  -H "Authorization: Bearer $TOKEN"

# List invoices
curl -X GET http://localhost:8000/api/invoices \
  -H "Authorization: Bearer $TOKEN"

# Record payment
curl -X POST http://localhost:8000/api/payments \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "reservation_id": 1,
    "amount": 150.00,
    "payment_method": "card"
  }'
```

---

## Database Verification

### Check Seeded Data
```bash
php artisan tinker

# Check users
>>> App\Models\User::all();

# Check rooms
>>> App\Models\Room::all();

# Check room types
>>> App\Models\RoomType::all();

# Check roles
>>> App\Models\Role::all();

# Exit
>>> exit
```

---

## Running Tests

### Run All Tests
```bash
php artisan test
```

### Run Specific Test
```bash
php artisan test tests/Feature/AuthTest.php
```

### Run with Coverage
```bash
php artisan test --coverage
```

---

## Common Issues & Fixes

### Issue: Database Connection Error
**Solution:**
1. Check MySQL is running: `mysql -u root -p`
2. Create database: `CREATE DATABASE hotel_hms;`
3. Update .env with correct credentials
4. Run: `php artisan migrate --seed`

### Issue: Key Not Generated
**Solution:**
```bash
php artisan key:generate
```

### Issue: Authentication Fails
**Solution:**
1. Check user exists: `php artisan tinker`
2. `App\Models\User::where('email', 'admin@hotel.local')->first();`
3. Reseed: `php artisan migrate:reset && php artisan migrate --seed`

### Issue: CORS Errors
**Solution:**
Add to `bootstrap/app.php` in middleware section:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web([
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
    ]);
    
    $middleware->api([
        'throttle:60,1',
    ]);
})
```

### Issue: Cache Issues
**Solution:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## Performance Testing

### Load Test Endpoints
```bash
# Using Apache Bench
ab -n 100 -c 10 http://localhost:8000/api/rooms

# Using wrk (if installed)
wrk -t12 -c400 -d30s http://localhost:8000/api/rooms
```

### Query Performance
```bash
# Enable query logging
php artisan tinker
>>> DB::enableQueryLog();
>>> App\Models\Room::with('roomType')->get();
>>> DB::getQueryLog();
```

---

## Backend Verification Checklist

- [ ] PHP 8.2+ installed
- [ ] Composer installed
- [ ] MySQL running
- [ ] `.env` file configured
- [ ] `php artisan key:generate` run
- [ ] `composer install` completed
- [ ] `php artisan migrate --seed` successful
- [ ] `php artisan serve` running
- [ ] `/api/health` returns 200 OK
- [ ] `/api/login` returns token
- [ ] All seeded users can login
- [ ] Room availability endpoint works
- [ ] Reservations can be created
- [ ] Invoices can be generated
- [ ] Payments can be recorded
- [ ] Tests pass: `php artisan test`

---

## Postman Collection

Import this JSON into Postman:

```json
{
  "info": {
    "name": "HMS API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Auth",
      "item": [
        {
          "name": "Login",
          "request": {
            "method": "POST",
            "url": "{{base_url}}/api/login",
            "body": {
              "mode": "raw",
              "raw": "{\"email\":\"admin@hotel.local\",\"password\":\"password\"}"
            }
          }
        }
      ]
    },
    {
      "name": "Rooms",
      "item": [
        {
          "name": "List Rooms",
          "request": {
            "method": "GET",
            "url": "{{base_url}}/api/rooms",
            "header": [
              {"key": "Authorization", "value": "Bearer {{token}}"}
            ]
          }
        }
      ]
    }
  ],
  "variable": [
    {"key": "base_url", "value": "http://localhost:8000"},
    {"key": "token", "value": ""}
  ]
}
```

---

## Deployment Verification

Before deployment, verify:
1. All migrations run cleanly
2. No error logs in `storage/logs`
3. All API endpoints respond
4. Database backups configured
5. HTTPS configured
6. Environment variables set correctly
7. Cache cleared
8. Assets compiled

---

For more information, see API_DOCUMENTATION.md
