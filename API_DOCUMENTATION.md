# Hotel Management System - Backend API Documentation

## Overview
This is a comprehensive REST API for managing hotel operations including rooms, guests, reservations, and billing.

## Base URL
```
http://localhost:8000/api
```

## Authentication
All endpoints (except `/api/login`) require authentication using Bearer tokens from Laravel Sanctum.

### Request Headers
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

---

## Authentication Endpoints

### Login
**POST** `/api/login`

Request:
```json
{
    "email": "admin@hotel.local",
    "password": "password"
}
```

Response (200):
```json
{
    "user": {
        "id": 1,
        "name": "Admin",
        "email": "admin@hotel.local",
        "role_id": 1,
        "is_active": true
    },
    "token": "1|xxxxxxxxxxxx"
}
```

### Logout
**POST** `/api/logout`
- Requires: Authentication

Response (200):
```json
{
    "message": "Logged out successfully"
}
```

### Get Current User
**GET** `/api/me`
- Requires: Authentication

Response (200):
```json
{
    "id": 1,
    "name": "Admin",
    "email": "admin@hotel.local"
}
```

---

## Room Endpoints

### List All Rooms
**GET** `/api/rooms`
- Requires: Authentication

Query Parameters:
- `status` (optional): available | occupied | maintenance | reserved

Response (200):
```json
[
    {
        "id": 1,
        "room_number": "101-1",
        "room_type_id": 1,
        "status": "available",
        "price_per_night": 50.00,
        "floor": 1,
        "room_type": { ... },
        "images": [ ... ]
    }
]
```

### Get Room Details
**GET** `/api/rooms/{id}`
- Requires: Authentication

Response (200):
```json
{
    "id": 1,
    "room_number": "101-1",
    "room_type_id": 1,
    "status": "available",
    "price_per_night": 50.00,
    "floor": 1,
    "room_type": { ... },
    "images": [ ... ]
}
```

### Create Room
**POST** `/api/rooms`
- Requires: Authentication (Admin only)

Request:
```json
{
    "room_number": "201-1",
    "room_type_id": 1,
    "price_per_night": 50.00,
    "floor": 2,
    "status": "available",
    "notes": "Near elevator"
}
```

Response (201):
```json
{
    "id": 16,
    "room_number": "201-1",
    ...
}
```

### Update Room
**PUT** `/api/rooms/{id}`
- Requires: Authentication (Admin/Receptionist)

Request:
```json
{
    "status": "maintenance",
    "price_per_night": 60.00
}
```

Response (200):
```json
{
    "id": 1,
    "status": "maintenance",
    ...
}
```

### Delete Room
**DELETE** `/api/rooms/{id}`
- Requires: Authentication (Admin only)

Response (200):
```json
{
    "message": "Room deleted"
}
```

### Check Room Availability
**GET** `/api/rooms/availability`
- Requires: Authentication

Query Parameters:
- `check_in_date` (required): YYYY-MM-DD
- `check_out_date` (required): YYYY-MM-DD

Response (200):
```json
[
    {
        "id": 1,
        "room_number": "101-1",
        "room_type": { ... },
        ...
    }
]
```

---

## Guest Endpoints

### List All Guests
**GET** `/api/guests`
- Requires: Authentication

Response (200):
```json
[
    {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com",
        "phone": "555-0100",
        "id_number": "ID123456",
        "address": "123 Main St",
        "city": "New York",
        "country": "USA",
        "postal_code": "10001"
    }
]
```

### Get Guest Details
**GET** `/api/guests/{id}`
- Requires: Authentication

Response (200):
```json
{
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "reservations": [ ... ]
}
```

### Register Guest
**POST** `/api/guests`
- Requires: Authentication

Request:
```json
{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "555-0100",
    "id_number": "ID123456",
    "address": "123 Main St",
    "city": "New York",
    "country": "USA",
    "postal_code": "10001"
}
```

Response (201):
```json
{
    "id": 5,
    "first_name": "John",
    ...
}
```

### Update Guest
**PUT** `/api/guests/{id}`
- Requires: Authentication

Request:
```json
{
    "phone": "555-0101",
    "address": "456 Oak Ave"
}
```

Response (200):
```json
{
    "id": 1,
    ...
}
```

### Delete Guest
**DELETE** `/api/guests/{id}`
- Requires: Authentication (Admin only)

Response (200):
```json
{
    "message": "Guest deleted"
}
```

---

## Reservation Endpoints

### List All Reservations
**GET** `/api/reservations`
- Requires: Authentication

Response (200):
```json
[
    {
        "id": 1,
        "guest_id": 1,
        "room_id": 1,
        "check_in_date": "2026-01-15",
        "check_out_date": "2026-01-18",
        "status": "confirmed",
        "number_of_guests": 2,
        "total_amount": 150.00,
        "guest": { ... },
        "room": { ... },
        "invoice": { ... }
    }
]
```

### Get Reservation Details
**GET** `/api/reservations/{id}`
- Requires: Authentication

Response (200):
```json
{
    "id": 1,
    "guest_id": 1,
    "room_id": 1,
    "check_in_date": "2026-01-15",
    "check_out_date": "2026-01-18",
    "status": "confirmed",
    "guest": { ... },
    "room": { ... },
    "invoice": { ... },
    "payments": [ ... ]
}
```

### Create Reservation
**POST** `/api/reservations`
- Requires: Authentication

Request:
```json
{
    "guest_id": 1,
    "room_id": 1,
    "check_in_date": "2026-01-15",
    "check_out_date": "2026-01-18",
    "number_of_guests": 2,
    "notes": "Special request: High floor"
}
```

Response (201):
```json
{
    "id": 5,
    "guest_id": 1,
    "room_id": 1,
    "check_in_date": "2026-01-15",
    "check_out_date": "2026-01-18",
    "status": "confirmed",
    "total_amount": 150.00
}
```

### Update Reservation
**PUT** `/api/reservations/{id}`
- Requires: Authentication

Request:
```json
{
    "check_out_date": "2026-01-20",
    "number_of_guests": 3
}
```

Response (200):
```json
{
    "id": 1,
    ...
}
```

### Check In Guest
**POST** `/api/reservations/{id}/check-in`
- Requires: Authentication (Receptionist/Admin)

Response (200):
```json
{
    "message": "Guest checked in"
}
```

### Check Out Guest
**POST** `/api/reservations/{id}/check-out`
- Requires: Authentication (Receptionist/Admin)

Response (200):
```json
{
    "message": "Guest checked out"
}
```

### Cancel Reservation
**POST** `/api/reservations/{id}/cancel`
- Requires: Authentication

Response (200):
```json
{
    "message": "Reservation cancelled"
}
```

---

## Billing & Invoice Endpoints

### Create Invoice
**POST** `/api/invoices/create/{reservationId}`
- Requires: Authentication

Response (200):
```json
{
    "id": 1,
    "reservation_id": 1,
    "invoice_number": "INV-1-1234567890",
    "subtotal": 150.00,
    "tax": 15.00,
    "total": 165.00,
    "status": "draft"
}
```

### List All Invoices
**GET** `/api/invoices`
- Requires: Authentication

Response (200):
```json
[
    {
        "id": 1,
        "reservation_id": 1,
        "invoice_number": "INV-1-1234567890",
        "subtotal": 150.00,
        "tax": 15.00,
        "total": 165.00,
        "status": "issued",
        "issued_at": "2026-01-15T10:30:00Z"
    }
]
```

### Get Invoice Details
**GET** `/api/invoices/{id}`
- Requires: Authentication

Response (200):
```json
{
    "id": 1,
    "reservation": { ... },
    "invoice_number": "INV-1-1234567890",
    "subtotal": 150.00,
    "tax": 15.00,
    "total": 165.00,
    "payments": [ ... ]
}
```

### Record Payment
**POST** `/api/payments`
- Requires: Authentication

Request:
```json
{
    "reservation_id": 1,
    "amount": 165.00,
    "payment_method": "card",
    "reference_number": "TXN-123456"
}
```

Response (201):
```json
{
    "id": 1,
    "reservation_id": 1,
    "amount": 165.00,
    "payment_method": "card",
    "status": "completed",
    "paid_at": "2026-01-15T10:45:00Z"
}
```

---

## Error Responses

### 400 Bad Request
```json
{
    "message": "Validation error",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

### 401 Unauthorized
```json
{
    "message": "Unauthenticated"
}
```

### 403 Forbidden
```json
{
    "message": "This action is unauthorized"
}
```

### 404 Not Found
```json
{
    "message": "Resource not found"
}
```

### 422 Unprocessable Entity
```json
{
    "message": "Login credentials invalid"
}
```

### 500 Internal Server Error
```json
{
    "message": "Internal server error"
}
```

---

## Status Codes

- `200 OK` — Successful GET, PUT
- `201 Created` — Successful POST
- `204 No Content` — Successful DELETE
- `400 Bad Request` — Invalid request data
- `401 Unauthorized` — Missing authentication
- `403 Forbidden` — Insufficient permissions
- `404 Not Found` — Resource not found
- `422 Unprocessable Entity` — Validation failed
- `500 Internal Server Error` — Server error

---

## Testing with cURL

### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@hotel.local","password":"password"}'
```

### Get Rooms
```bash
curl -X GET http://localhost:8000/api/rooms \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

### Create Reservation
```bash
curl -X POST http://localhost:8000/api/reservations \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "guest_id": 1,
    "room_id": 1,
    "check_in_date": "2026-01-15",
    "check_out_date": "2026-01-18",
    "number_of_guests": 2
  }'
```

---

## Testing with Postman

1. Import the API documentation
2. Set environment variable: `base_url=http://localhost:8000`
3. Set environment variable: `token=YOUR_LOGIN_TOKEN`
4. Use `{{base_url}}/api/endpoint` in requests
5. Add header: `Authorization: Bearer {{token}}`

---

## Rate Limiting
Currently no rate limiting is applied. Consider implementing in production.

## Pagination
Currently all endpoints return full results. Implement pagination for large datasets:
```
GET /api/rooms?page=1&per_page=10
```

---

For more details, refer to the README.md and SETUP_INSTRUCTIONS.md files.
