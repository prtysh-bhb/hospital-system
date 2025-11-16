<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Front Desk') - MediCare HMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-white shadow-lg">
            <div class="p-6 border-b">
                <h1 class="text-2xl font-bold text-sky-700">MediCare HMS</h1>
                <p class="text-sm text-gray-500">Front Desk</p>
            </div>
            <nav class="p-4">
                <a href="{{ route('frontdesk.dashboard') }}" class="flex items-center px-4 py-3 mb-2 {{ request()->routeIs('frontdesk.dashboard') ? 'text-white bg-sky-600' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg">
                    <span class="ml-2">Dashboard</span>
                </a>
                <a href="{{ route('frontdesk.add-appointment') }}" class="flex items-center px-4 py-3 mb-2 {{ request()->routeIs('frontdesk.add-appointment') ? 'text-white bg-sky-600' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg">
                    <span class="ml-2">Add Appointment</span>
                </a>
                <a href="{{ route('frontdesk.doctor-schedule') }}" class="flex items-center px-4 py-3 mb-2 {{ request()->routeIs('frontdesk.doctor-schedule') ? 'text-white bg-sky-600' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg">
                    <span class="ml-2">Doctor Schedule</span>
                </a>
                <a href="{{ route('frontdesk.patients') }}" class="flex items-center px-4 py-3 mb-2 {{ request()->routeIs('frontdesk.patients') ? 'text-white bg-sky-600' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg">
                    <span class="ml-2">Patients</span>
                </a>
                <a href="{{ route('frontdesk.history') }}" class="flex items-center px-4 py-3 mb-2 {{ request()->routeIs('frontdesk.history') ? 'text-white bg-sky-600' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg">
                    <span class="ml-2">History</span>
                </a>
                <a href="{{ route('login') }}" class="flex items-center px-4 py-3 mb-2 mt-8 text-red-600 hover:bg-red-50 rounded-lg">
                    <span class="ml-2">Logout</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-8 py-4">
                    <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title')</h2>
                    <div class="flex items-center space-x-4">
                        @yield('header-actions')
                        <div class="flex items-center space-x-3">
                            <img src="https://ui-avatars.com/api/?name=Maria+Garcia&background=0ea5e9&color=fff"
                                 class="w-10 h-10 rounded-full" alt="Staff">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Maria Garcia</p>
                                <p class="text-xs text-gray-500">Front Desk</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
