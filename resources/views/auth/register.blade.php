<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Fiber Core Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <!-- Register Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mb-4">
                    <i data-lucide="user-plus" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Buat Akun Baru</h1>
                <p class="text-gray-600 mt-2">Setup akun untuk sistem manajemen fiber</p>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap
                    </label>
                    <div class="relative">
                        <i data-lucide="user" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"></i>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            required
                            value="{{ old('name') }}"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                            placeholder="Nama lengkap Anda"
                        />
                    </div>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <i data-lucide="mail" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            value="{{ old('email') }}"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                            placeholder="your@email.com"
                        />
                    </div>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Role
                    </label>
                    <div class="relative">
                        <i data-lucide="shield" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"></i>
                        <select
                            id="role"
                            name="role"
                            required
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror"
                            onchange="toggleRegionField()"
                        >
                            <option value="">Pilih Role</option>
                            <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="regional" {{ old('role') == 'regional' ? 'selected' : '' }}>Regional Admin</option>
                        </select>
                    </div>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="regionField" class="hidden">
                    <label for="region" class="block text-sm font-medium text-gray-700 mb-2">
                        Region
                    </label>
                    <div class="relative">
                        <i data-lucide="map-pin" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"></i>
                        <select
                            id="region"
                            name="region"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('region') border-red-500 @enderror"
                        >
                            <option value="">Pilih Region</option>
                            <option value="Bali" {{ old('region') == 'Bali' ? 'selected' : '' }}>Bali</option>
                            <option value="Nusa Tenggara Barat" {{ old('region') == 'Nusa Tenggara Barat' ? 'selected' : '' }}>Nusa Tenggara Barat</option>
                            <option value="Nusa Tenggara Timur" {{ old('region') == 'Nusa Tenggara Timur' ? 'selected' : '' }}>Nusa Tenggara Timur</option>
                            <option value="Denpasar Utara" {{ old('region') == 'Denpasar Utara' ? 'selected' : '' }}>Denpasar Utara</option>
                            <option value="Denpasar Selatan" {{ old('region') == 'Denpasar Selatan' ? 'selected' : '' }}>Denpasar Selatan</option>
                            <option value="Badung" {{ old('region') == 'Badung' ? 'selected' : '' }}>Badung</option>
                            <option value="Gianyar" {{ old('region') == 'Gianyar' ? 'selected' : '' }}>Gianyar</option>
                            <option value="Tabanan" {{ old('region') == 'Tabanan' ? 'selected' : '' }}>Tabanan</option>
                            <option value="Klungkung" {{ old('region') == 'Klungkung' ? 'selected' : '' }}>Klungkung</option>
                            <option value="Buleleng" {{ old('region') == 'Buleleng' ? 'selected' : '' }}>Buleleng</option>
                        </select>
                    </div>
                    @error('region')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                            placeholder="••••••••"
                        />
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password
                    </label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"></i>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="••••••••"
                        />
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200 flex items-center justify-center gap-2"
                >
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                    Buat Akun
                </button>
            </form>

            <!-- Login Link -->
            <div class="text-center mt-6">
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    Sudah punya akun? Login di sini
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        function toggleRegionField() {
            const roleSelect = document.getElementById('role');
            const regionField = document.getElementById('regionField');
            const regionSelect = document.getElementById('region');
            
            if (roleSelect.value === 'regional') {
                regionField.classList.remove('hidden');
                regionSelect.required = true;
            } else {
                regionField.classList.add('hidden');
                regionSelect.required = false;
                regionSelect.value = '';
            }
        }

        // Initialize on page load
        toggleRegionField();
    </script>
</body>
</html>