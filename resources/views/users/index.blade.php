@extends('layouts.app')

@section('title', 'Kelola User - Fiber Core Management')

@section('content')
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <i data-lucide="users" class="w-8 h-8 text-blue-600"></i>
                    Kelola User
                </h1>
                <p class="text-gray-600 mt-2">Manajemen akun user dan akses regional</p>
            </div>
            <a href="{{ route('users.create') }}"
               class="inline-flex items-center gap-2 bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
                Tambah User
            </a>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-100 to-blue-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Region</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Dibuat</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-blue-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i data-lucide="user" class="w-4 h-4 text-blue-600"></i>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->isSuperAdmin())
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                        <i data-lucide="crown" class="w-3 h-3 inline mr-1"></i>
                                        Super Admin
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                        <i data-lucide="shield" class="w-3 h-3 inline mr-1"></i>
                                        Regional Admin
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->region)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                        {{ $user->region }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('users.edit', $user) }}"
                                       class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition">
                                        <i data-lucide="edit" class="w-3 h-3"></i>
                                        Edit
                                    </a>
                                    
                                    @if($user->id !== Auth::id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="if(confirm('Yakin ingin menghapus user ini?')) { this.closest('form').submit(); }"
                                                    class="inline-flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-500">
                                Belum ada user yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection