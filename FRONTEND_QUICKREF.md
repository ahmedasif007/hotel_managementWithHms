# 🎨 Frontend Quick Reference Guide

## Getting Started Quickly

### 1. Start Development
```bash
# Terminal 1: Frontend assets
npm run dev

# Terminal 2: Laravel server
php artisan serve
```

Visit: http://localhost:8000

---

## 📍 Key URLs

### Authentication
- Login: `/login`
- Register: `/register`
- Logout: POST `/logout`

### Admin Dashboard
- Dashboard: `/admin/dashboard`
- Rooms: `/admin/rooms`
- Reservations: `/admin/reservations`
- Invoices: `/admin/invoices`

### Guest Interface
- Dashboard: `/guest/dashboard`
- Browse Rooms: `/guest/rooms`
- My Reservations: `/guest/reservations`
- My Invoices: `/guest/invoices`

---

## 🔑 Demo Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@hotel.local | password |
| Receptionist | receptionist@hotel.local | password |
| Staff | staff@hotel.local | password |
| Guest | guest@hotel.local | password |

---

## 📁 View Files Structure

```
resources/views/
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── admin/
│   ├── dashboard.blade.php
│   ├── rooms/
│   ├── reservations/
│   └── invoices/
├── guest/
│   ├── dashboard.blade.php
│   ├── rooms.blade.php
│   ├── reservations/
│   └── invoices/
└── layouts/
    └── app.blade.php
```

---

## 🔨 Controller Files

```
app/Http/Controllers/
├── Auth/
│   ├── LoginController.php
│   └── RegisterController.php
├── Admin/
│   ├── DashboardController.php
│   ├── RoomController.php
│   ├── ReservationController.php
│   └── InvoiceController.php
└── Guest/
    ├── GuestDashboardController.php
    ├── GuestRoomController.php
    ├── GuestReservationController.php
    └── GuestInvoiceController.php
```

---

## 🛣️ Quick Route Reference

### Create New Page
1. Create view: `resources/views/page-name.blade.php`
2. Create controller method
3. Add route in `routes/web.php`
4. Update navigation links

### Example:
```php
// In routes/web.php
Route::get('/my-page', [MyController::class, 'show']);

// In MyController.php
public function show() {
    return view('my-page', $data);
}
```

---

## 🎨 Tailwind Classes Quick Reference

### Spacing
- `p-4` - Padding 1rem
- `m-4` - Margin 1rem
- `mb-4` - Margin bottom
- `px-4` - Horizontal padding

### Colors
- `bg-blue-600` - Blue background
- `text-white` - White text
- `text-gray-600` - Gray text
- `border-gray-300` - Gray border

### Layout
- `flex` - Flexbox
- `grid` - Grid layout
- `grid-cols-3` - 3 column grid
- `gap-4` - Gap between items

### Responsive
- `md:grid-cols-2` - 2 columns on medium+
- `lg:col-span-2` - 2 column span on large+
- `hidden md:block` - Hidden on small, visible on medium+

---

## 🧩 Common Components

### Card
```html
<div class="bg-white rounded-lg shadow p-6">
    Content here
</div>
```

### Button
```html
<button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
    Click Me
</button>
```

### Badge
```html
<span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
    Active
</span>
```

### Form Input
```html
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Label</label>
    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
</div>
```

---

## 📋 Form Validation

### In Controller
```php
$validated = $request->validate([
    'email' => 'required|email',
    'password' => 'required|min:8',
]);
```

### In Blade (Display Errors)
```blade
@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

@error('email')
    <span class="text-red-600">{{ $message }}</span>
@enderror
```

---

## 🔐 Authorization

### In Blade
```blade
@can('create-rooms')
    <a href="/rooms/create">Add Room</a>
@endcan

@role('admin')
    <div>Admin Only</div>
@endrole
```

### In Controller
```php
$this->authorize('update', $room);

if (auth()->user()->cannot('delete', $room)) {
    abort(403);
}
```

---

## 📝 Common Patterns

### Displaying Data
```blade
@foreach($items as $item)
    <div>{{ $item->name }}</div>
@empty
    <p>No items</p>
@endforeach
```

### Conditional Display
```blade
@if($condition)
    Show this
@elseif($other)
    Or this
@else
    Or this
@endif
```

### Form CSRF Token
```blade
<form method="POST">
    @csrf
    <!-- form fields -->
</form>
```

### Method Spoofing
```blade
<form method="POST" action="/item/{{ $id }}">
    @csrf
    @method('PUT')
    <!-- form fields -->
</form>
```

---

## 🔗 Navigation

### Update Navigation Links
**File:** `resources/views/components/navbar.blade.php`
- Update logo link
- Add/remove navigation items
- Update user menu

**File:** `resources/views/components/sidebar.blade.php`
- Add new menu sections
- Update permission checks
- Add new routes

---

## 🎯 Development Checklist

When creating a new feature:
- [ ] Create blade view file
- [ ] Create controller method
- [ ] Add route in `web.php`
- [ ] Update navigation menus
- [ ] Test with all roles
- [ ] Check responsive design
- [ ] Verify form validation
- [ ] Add error messages
- [ ] Test database operations

---

## 🐛 Debugging Tips

### Enable Debug Mode
In `.env`:
```
APP_DEBUG=true
```

### Log Debugging
```php
Log::info('Debug message', $data);
dd($variable); // Die and dump
```

### View Data
```blade
<!-- In blade -->
{{ dd($variable) }}
@dd($data)
```

---

## 📱 Responsive Breakpoints

- **Small (mobile):** < 640px
- **Medium (tablet):** 768px+
- **Large (laptop):** 1024px+
- **XL (desktop):** 1280px+

**Usage:**
```html
<!-- Hidden on small, visible on medium+ -->
<div class="hidden md:block">Desktop only</div>

<!-- Show different for different sizes -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
```

---

## 🎓 Learning Resources

- **Blade Templating:** Laravel docs
- **Tailwind CSS:** tailwindcss.com
- **Alpine.js:** alpinejs.dev
- **Laravel Conventions:** laravel.com/docs

---

## 💡 Tips & Tricks

1. **Reuse Components:** Create shared Blade components in `resources/views/components/`
2. **Asset Compilation:** Run `npm run build` for production
3. **Cache Busting:** Vite handles this automatically
4. **Optimize Images:** Compress images before uploading
5. **Lazy Loading:** Use `lazy` class with Tailwind
6. **Forms:** Always include `@csrf` token
7. **Validation:** Show friendly error messages
8. **Mobile First:** Design for mobile, then enhance

---

## 📞 Support

For issues or questions:
1. Check the documentation files
2. Review controller methods
3. Inspect network tab in browser dev tools
4. Check Laravel logs in `storage/logs/`
5. Verify database relationships in models

---

**Happy coding! 🚀**
