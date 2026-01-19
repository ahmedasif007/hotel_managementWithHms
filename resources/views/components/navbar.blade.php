<!-- Navigation Bar -->
<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold">H</span>
                </div>
                <a href="/" class="font-bold text-xl text-gray-900">Hotel HMS</a>
            </div>

            <!-- Center Navigation -->
            @auth
                <div class="hidden md:flex space-x-8">
                    <a href="/dashboard" class="text-gray-700 hover:text-blue-600 transition">Dashboard</a>
                    @can('view-rooms')
                        <a href="/rooms" class="text-gray-700 hover:text-blue-600 transition">Rooms</a>
                    @endcan
                    @can('view-reservations')
                        <a href="/reservations" class="text-gray-700 hover:text-blue-600 transition">Reservations</a>
                    @endcan
                    @can('view-invoices')
                        <a href="/invoices" class="text-gray-700 hover:text-blue-600 transition">Invoices</a>
                    @endcan
                </div>
            @endauth

            <!-- Right Side -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- User Menu -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600">
                            <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}" alt="Profile" class="w-8 h-8 rounded-full">
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden" :class="{ 'hidden': !open }">
                            <a href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="/settings" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Settings</a>
                            <hr class="my-2">
                            <form method="POST" action="/logout" class="inline-block w-full">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="/login" class="text-gray-700 hover:text-blue-600">Login</a>
                    <a href="/register" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
