<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: false }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fiber Core Management')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</head>

<body class="bg-gray-50 flex">

    <!-- Overlay mobile -->
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden"
        x-transition.opacity></div>

    <!-- Sidebar -->
    <aside
        class="fixed top-0 left-0 h-full w-64 bg-white shadow-md border-r flex flex-col justify-between z-30 transform transition-transform duration-200 ease-in-out
               lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div>
            <!-- Logo -->
            <div class="p-6 border-b flex justify-between items-center">
                <a href="{{ route('fiber-cores.index') }}" class="flex items-center gap-2 text-xl font-bold text-gray-900 hover:text-blue-600">
                    <i data-lucide="server" class="w-6 h-6"></i>
                    Fiber Core
                </a>
                <!-- Tombol close di mobile -->
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-600 hover:text-red-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- User Info -->
            <div class="p-6 border-b text-sm">
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="user" class="w-4 h-4 text-gray-500"></i>
                    <span class="text-gray-700">{{ Auth::user()->name }}</span>
                </div>
                <div>
                    @if(Auth::user()->isSuperAdmin())
                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold inline-flex items-center gap-1">
                        <i data-lucide="crown" class="w-3 h-3"></i> Super Admin
                    </span>
                    @else
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold inline-flex items-center gap-1">
                        <i data-lucide="map-pin" class="w-3 h-3"></i> {{ Auth::user()->region }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav class="p-4 space-y-2">
                <a href="{{ route('fiber-cores.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-blue-50 {{ request()->routeIs('fiber-cores.index') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-600' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                </a>

                <a href="#"
                    class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-blue-50 {{ request()->routeIs('fiber-cores.Vlan') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-600' }}">
                    <i data-lucide="icon-network" class="w-4 h-4"></i> Vlan
                </a>

                @if(Auth::user()->isSuperAdmin())
                <a href="{{ route('users.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-blue-50 {{ request()->routeIs('users.*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-600' }}">
                    <i data-lucide="users" class="w-4 h-4"></i> Kelola User
                </a>
                @endif
            </nav>
        </div>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="p-4 border-t">
            @csrf
            <button type="submit" class="flex items-center gap-2 text-gray-600 hover:text-red-600 text-sm">
                <i data-lucide="log-out" class="w-4 h-4"></i> Logout
            </button>
        </form>
    </aside>

    <!-- Konten -->
    <div class="flex-1 lg:ml-64 w-full">
        <!-- Header mobile -->
        <div class="bg-white shadow-sm border-b px-4 py-3 flex items-center justify-between lg:hidden">
            <button @click="sidebarOpen = true" class="text-gray-600 hover:text-gray-900">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
            <span class="font-semibold text-gray-800">@yield('title', 'Fiber Core Management')</span>
            <div></div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="px-6 pt-6">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6"
                x-data="{ show: true }" x-show="show">
                <div class="flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="text-green-500 hover:text-green-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="px-6 pt-6">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6"
                x-data="{ show: true }" x-show="show">
                <div class="flex justify-between items-center">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-500 hover:text-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Konten Halaman -->
        <main class="px-6 py-6">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="py-6 text-center text-gray-500 text-sm border-t">
            <p>&copy; {{ date('Y') }} Fiber Core Management System. Built with Laravel & Tailwind CSS.</p>
        </footer>
    </div>

    <script>
        function reinitializeIcons() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    </script>

    @stack('scripts')
</body>

</html>
