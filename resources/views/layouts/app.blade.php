<!DOCTYPE html>
<html lang="id">
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
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b mb-6">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <h1 class="text-xl font-bold text-gray-900">
                        <a href="{{ route('fiber-cores.index') }}" class="hover:text-blue-600 flex items-center gap-2">
                            <i data-lucide="server" class="w-6 h-6"></i>
                            Fiber Core Management
                        </a>
                    </h1>
                </div>
                
                <div class="flex items-center space-x-6">
                    <!-- User Info -->
                    <div class="flex items-center gap-3 text-sm">
                        <div class="flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4 text-gray-500"></i>
                            <span class="text-gray-700">{{ Auth::user()->name }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            @if(Auth::user()->isSuperAdmin())
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                    <i data-lucide="crown" class="w-3 h-3 inline mr-1"></i>
                                    Super Admin
                                </span>
                            @else
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                    <i data-lucide="map-pin" class="w-3 h-3 inline mr-1"></i>
                                    {{ Auth::user()->region }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <a href="{{ route('fiber-cores.index') }}"
                       class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('fiber-cores.index') ? 'font-semibold text-blue-600' : '' }}">
                        Dashboard
                    </a>

                    @if(Auth::user()->isSuperAdmin())
                        <a href="{{ route('users.index') }}"
                           class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('users.*') ? 'font-semibold text-blue-600' : '' }}">
                            Kelola User
                        </a>
                    @endif

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-red-600 flex items-center gap-1 text-sm">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-6 mb-6">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" x-data="{ show: true }" x-show="show">
                <div class="flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="text-green-500 hover:text-green-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-6 mb-6">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" x-data="{ show: true }" x-show="show">
                <div class="flex justify-between items-center">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-500 hover:text-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-12 py-8 text-center text-gray-500 text-sm">
        <div class="max-w-7xl mx-auto px-6">
            <p>&copy; {{ date('Y') }} Fiber Core Management System. Built with Laravel & Tailwind CSS.</p>
        </div>
    </footer>

    <!-- Icons Script -->
    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Reinitialize icons after AJAX content update
        function reinitializeIcons() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Confirm delete function
        function confirmDelete(form) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                form.submit();
            }
        }

        // Show full text function
        function showFullText(text) {
            alert(text);
        }
    </script>

    @stack('scripts')
</body>
</html>