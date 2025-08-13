@extends('layouts.app')

@section('title', 'Edit User - Fiber Core Management')

@section('content')
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <i data-lucide="user-edit" class="w-6 h-6 text-blue-600"></i>
                    Edit User: {{ $user->name }}
                </h2>
                <a href="{{ route('users.index') }}"
                   class="text-gray-600 hover:text-gray-800 flex items-center gap-2 border px-3 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali
                </a>
            </div>

            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="name"
                                required
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                value="{{ old('name', $user->name) }}"
                                placeholder="Nama lengkap user"
                            />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="email"
                                name="email"
                                required
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                                value="{{ old('email', $user->email) }}"
                                placeholder="user@example.com"
                            />
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Password Baru
                            </label>
                            <input
                                type="password"
                                name="password"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                                placeholder="Kosongkan jika tidak ingin mengubah"
                            />
                            <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.</p>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Konfirmasi Password Baru
                            </label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ulangi password baru"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="role"
                                id="role"
                                required
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror"
                                onchange="toggleRegionField()"
                            >
                                <option value="">Pilih Role</option>
                                <option value="superadmin" {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="regional" {{ old('role', $user->role) == 'regional' ? 'selected' : '' }}>Regional Admin</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="regionField" class="{{ old('role', $user->role) == 'regional' ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Region <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="region"
                                id="region"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('region') border-red-500 @enderror"
                            >
                                <option value="">Pilih Region</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region }}" {{ old('region', $user->region) == $region ? 'selected' : '' }}>
                                        {{ $region }}
                                    </option>
                                @endforeach
                            </select>
                            @error('region')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('users.index') }}"
                           class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </a>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
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
    document.addEventListener('DOMContentLoaded', function() {
        toggleRegionField();
    });
</script>
@endpush