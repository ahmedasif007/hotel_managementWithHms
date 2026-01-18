<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Hotel Management System</h1>
            <div>
                <a href="/login" class="hover:underline">Login</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-10">
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-3xl font-bold mb-4">Welcome to HMS</h2>
            <p class="text-gray-700 mb-4">
                A comprehensive Hotel Management System for managing room bookings, guest information, and billing.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="bg-blue-50 p-4 rounded">
                    <h3 class="font-bold text-blue-600">Rooms</h3>
                    <p class="text-sm text-gray-600">Manage rooms and availability</p>
                </div>
                <div class="bg-green-50 p-4 rounded">
                    <h3 class="font-bold text-green-600">Bookings</h3>
                    <p class="text-sm text-gray-600">Handle reservations and check-ins</p>
                </div>
                <div class="bg-purple-50 p-4 rounded">
                    <h3 class="font-bold text-purple-600">Billing</h3>
                    <p class="text-sm text-gray-600">Generate invoices and payments</p>
                </div>
            </div>

            <div class="mt-6">
                <p class="text-sm text-gray-500">Default Credentials:</p>
                <ul class="text-sm text-gray-600 mt-2">
                    <li><strong>Admin:</strong> admin@hotel.local / password</li>
                    <li><strong>Receptionist:</strong> receptionist@hotel.local / password</li>
                    <li><strong>Staff:</strong> staff@hotel.local / password</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
