# API Optimization Guide

## Performance Benchmarks

### Target Response Times
- List endpoints: < 500ms
- Single resource: < 200ms
- Complex operations: < 1000ms
- Health checks: < 100ms

---

## Query Optimization

### Eager Loading
```php
// ❌ Bad - N+1 query problem
$reservations = Reservation::all();
foreach ($reservations as $reservation) {
    echo $reservation->guest->name; // Executes separate query for each
}

// ✅ Good - Eager loading
$reservations = Reservation::with('guest', 'room', 'invoice')->get();
```

### Selective Queries
```php
// ❌ Bad - Returns unnecessary columns
$rooms = Room::all();

// ✅ Good - Select only needed columns
$rooms = Room::select('id', 'room_number', 'price_per_night')->get();
```

### Pagination
```php
// ❌ Bad - Loads all records
$rooms = Room::all();

// ✅ Good - Paginate large datasets
$rooms = Room::paginate(15);
```

### Indexing
```sql
-- Add database indexes
ALTER TABLE reservations ADD INDEX idx_guest_id(guest_id);
ALTER TABLE reservations ADD INDEX idx_room_id(room_id);
ALTER TABLE reservations ADD INDEX idx_check_in(check_in_date);
ALTER TABLE invoices ADD INDEX idx_reservation_id(reservation_id);
ALTER TABLE payments ADD INDEX idx_invoice_id(invoice_id);
```

---

## Caching Strategy

### Query Caching
```php
// Cache available rooms for 1 hour
$availableRooms = Cache::remember(
    'available_rooms_' . $checkInDate . '_' . $checkOutDate,
    3600,
    function () use ($checkInDate, $checkOutDate) {
        return Room::checkAvailability($checkInDate, $checkOutDate)->get();
    }
);
```

### Controller Caching
```php
public function getStatistics()
{
    $stats = Cache::remember('dashboard_stats', 3600, function () {
        return [
            'total_rooms' => Room::count(),
            'available_rooms' => Room::available()->count(),
            'occupied_rooms' => Room::occupied()->count(),
        ];
    });
    
    return response()->json($stats);
}
```

### Configuration Caching
```bash
# Cache configuration files
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Clear all caches
php artisan cache:clear
```

---

## Response Optimization

### JSON Size Reduction
```php
// ❌ Large response
return Room::with('type', 'images')->get();

// ✅ Optimized response with resources
return RoomResource::collection(
    Room::with('type')
        ->select('id', 'room_number', 'room_type_id', 'price_per_night')
        ->paginate(15)
);
```

### API Resource Optimization
```php
class RoomResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'room_number' => $this->room_number,
            'price_per_night' => $this->price_per_night,
            // Only include images if explicitly requested
            'images' => $this->when(
                $request->query('include') === 'images',
                RoomImageResource::collection($this->images)
            ),
        ];
    }
}
```

---

## Database Connection Pooling

### Configuration
```env
# .env
DB_POOL_SIZE=20
DB_POOL_TIMEOUT=30
```

### Implementation
```php
// config/database.php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST'),
    'pool' => [
        'min_connections' => 10,
        'max_connections' => 20,
    ],
],
```

---

## Load Balancing

### Round-Robin Configuration
```nginx
upstream laravel {
    server app1.local:9000;
    server app2.local:9000;
    server app3.local:9000;
}

server {
    listen 80;
    server_name api.hotel.com;
    
    location / {
        proxy_pass http://laravel;
    }
}
```

---

## Session Optimization

### Redis Session Store
```env
# .env
SESSION_DRIVER=redis
CACHE_DRIVER=redis
```

### Session Configuration
```php
// config/session.php
'driver' => env('SESSION_DRIVER', 'redis'),
'lifetime' => 120,
'expire_on_close' => false,
'encrypt' => true,
```

---

## API Rate Limiting

### Configuration
```php
// middleware/RateLimitMiddleware.php
public function handle(Request $request, Closure $next)
{
    $limit = 60;  // 60 requests
    $decay = 60;  // per minute
    
    if ($this->limiter->tooManyAttempts($key, $limit, $decay)) {
        return response()->json(['message' => 'Too many requests'], 429);
    }
    
    return $next($request);
}
```

### Apply to Routes
```php
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/api/rooms', 'RoomController@index');
});
```

---

## Compression

### Gzip Configuration
```nginx
# nginx.conf
gzip on;
gzip_min_length 1000;
gzip_proxied any;
gzip_types text/plain text/css text/javascript 
           application/json application/javascript 
           application/xml+rss application/rss+xml;
gzip_vary on;
```

---

## CDN Integration

### CloudFront Setup
```php
// Store file URLs with CloudFront domain
'CDN_DOMAIN' => env('CDN_DOMAIN', 'https://d123.cloudfront.net'),

// In migrations
Schema::create('room_images', function (Blueprint $table) {
    $table->id();
    $table->string('path');
    $table->string('cdn_url')->nullable();
});
```

---

## Monitoring Query Performance

### Laravel Debugbar
```bash
# Install
composer require barryvdh/laravel-debugbar --dev

# View query execution times
# Check in browser DevTools
```

### Database Query Logging
```php
// Log slow queries
if (DB::getQueryLog()) {
    foreach (DB::getQueryLog() as $query) {
        if ($query['time'] > 500) { // Log queries over 500ms
            \Log::warning('Slow query: ' . $query['query']);
        }
    }
}
```

---

## Best Practices

1. **Always use pagination** for large datasets
2. **Eager load relationships** to avoid N+1 queries
3. **Cache frequently accessed data** (configuration, statistics)
4. **Use API resources** for consistent response formatting
5. **Select only required columns** in queries
6. **Index frequently queried columns**
7. **Implement rate limiting** to prevent abuse
8. **Monitor query performance** regularly
9. **Use connection pooling** for better resource management
10. **Enable compression** for all responses

---

## Testing Performance

### Run Performance Tests
```bash
php artisan test tests/Performance/PerformanceTest.php
```

### Load Testing with ApacheBench
```bash
# Install ApacheBench
# Benchmark endpoints
ab -n 1000 -c 100 http://localhost:8000/api/rooms
```

### Load Testing with Artillery
```bash
# Install Artillery
npm install -g artillery

# Run load test
artillery quick --count 100 --num 1000 http://localhost:8000/api/rooms
```

---

## Metrics to Track

- Response time (ms)
- Throughput (requests/second)
- Error rate (%)
- Cache hit rate (%)
- Database query count
- Memory usage (MB)
- CPU usage (%)

---

**Last Updated:** 2024
**Version:** 1.0.0
