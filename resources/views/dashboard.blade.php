<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - HMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Hotel Management System</h1>
            <div>
                <span class="mr-4">{{ Auth::user()->name }}</span>
                <form action="/logout" method="POST" class="inline">
                    @csrf
                    <button class="hover:underline">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-10">
        <h2 class="text-2xl font-bold mb-4">Dashboard</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded shadow p-4">
                <h3 class="text-gray-500 text-sm">Total Rooms</h3>
                <p class="text-3xl font-bold">0</p>
            </div>
            <div class="bg-white rounded shadow p-4">
                <h3 class="text-gray-500 text-sm">Occupied Rooms</h3>
                <p class="text-3xl font-bold">0</p>
            </div>
            <div class="bg-white rounded shadow p-4">
                <h3 class="text-gray-500 text-sm">Active Reservations</h3>
                <p class="text-3xl font-bold">0</p>
            </div>
            <div class="bg-white rounded shadow p-4">
                <h3 class="text-gray-500 text-sm">Today's Revenue</h3>
                <p class="text-3xl font-bold">$0</p>
            </div>
        </div>

        <div class="bg-white rounded shadow p-6 mt-6">
            <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="/api/rooms" class="block bg-blue-500 text-white p-2 rounded hover:bg-blue-600">View Rooms</a>
                <a href="/api/reservations" class="block bg-green-500 text-white p-2 rounded hover:bg-green-600">View Reservations</a>
                <a href="/api/invoices" class="block bg-purple-500 text-white p-2 rounded hover:bg-purple-600">View Invoices</a>
            </div>
        </div>
    </div>
</body>
</html>
