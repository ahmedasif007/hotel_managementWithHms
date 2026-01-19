<!-- Sidebar -->
@auth
<aside class="w-64 bg-gray-800 text-white h-full">
    <div class="p-6 space-y-8">
        <!-- User Info -->
        <div class="border-b border-gray-700 pb-6">
            <p class="text-sm text-gray-400">Logged in as</p>
            <p class="font-semibold text-white">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-400">{{ auth()->user()->roles->first()?->name ?? 'User' }}</p>
        </div>

        <!-- Navigation Links -->
        <nav class="space-y-4">
            <!-- All Users -->
            <a href="/dashboard" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"></path>
                    <path d="M3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6z"></path>
                    <path d="M14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                </svg>
                <span>Dashboard</span>
            </a>

            @can('view-rooms')
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-gray-700 transition">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.5 1.5H5.25A1.25 1.25 0 004 2.75v14.5A1.25 1.25 0 005.25 18h9.5a1.25 1.25 0 001.25-1.25V6.5"></path>
                                <path d="M14 1.5v4M7 5.5h4M7 9h6M7 12.5h6"></path>
                            </svg>
                            <span>Rooms</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"></path>
                        </svg>
                    </button>
                    <div x-show="open" class="pl-8 space-y-2">
                        <a href="/rooms" class="block p-2 text-sm text-gray-300 hover:text-white">List Rooms</a>
                        @can('create-rooms')
                            <a href="/rooms/create" class="block p-2 text-sm text-gray-300 hover:text-white">Add Room</a>
                        @endcan
                    </div>
                </div>
            @endcan

            @can('view-reservations')
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-gray-700 transition">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path>
                            </svg>
                            <span>Reservations</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"></path>
                        </svg>
                    </button>
                    <div x-show="open" class="pl-8 space-y-2">
                        <a href="/reservations" class="block p-2 text-sm text-gray-300 hover:text-white">All Reservations</a>
                        <a href="/reservations/create" class="block p-2 text-sm text-gray-300 hover:text-white">New Reservation</a>
                    </div>
                </div>
            @endcan

            @can('view-invoices')
                <a href="/invoices" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4z"></path>
                    </svg>
                    <span>Invoices</span>
                </a>
            @endcan

            @can('view-guests')
                <a href="/guests" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 12a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                    </svg>
                    <span>Guests</span>
                </a>
            @endcan

            @can('view-users')
                <a href="/users" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                    </svg>
                    <span>Users</span>
                </a>
            @endcan

            @can('view-reports')
                <a href="/reports" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                    <span>Reports</span>
                </a>
            @endcan
        </nav>
    </div>
</aside>
@endauth
