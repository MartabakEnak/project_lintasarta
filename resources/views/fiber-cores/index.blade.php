@extends('layouts.app')

@section('title', 'Dashboard - Fiber Core Management')

@section('content')
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            Sistem Manajemen Core Fiber Optik
        </h1>
        <p class="text-gray-600">
            Kelola dan monitor status core fiber optik secara real-time berdasarkan region
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Core</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <div class="w-6 h-6 bg-blue-500 rounded"></div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Core Active</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
                </div>
                <i data-lucide="check-circle" class="w-12 h-12 text-green-500"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Core Inactive</p>
                    <p class="text-2xl font-bold text-gray-600">{{ $stats['inactive'] }}</p>
                </div>
                <i data-lucide="x-circle" class="w-12 h-12 text-gray-500"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Problem</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['problems'] }}</p>
                </div>
                <i data-lucide="alert-triangle" class="w-12 h-12 text-red-500"></i>
            </div>
        </div>
    </div>

    <!-- Regional Overview -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i data-lucide="map-pin" class="w-5 h-5"></i>
            Overview Regional
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($regionalStats as $stat)
                <div class="bg-gray-50 rounded-lg p-4">
   
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{
                            match($stat->region) {
                                'Denpasar Utara', 'Denpasar Selatan' => 'bg-purple-100 text-purple-800',
                                'Badung' => 'bg-orange-100 text-orange-800',
                                'Gianyar' => 'bg-teal-100 text-teal-800',
                                'Tabanan' => 'bg-indigo-100 text-indigo-800',
                                'Klungkung' => 'bg-pink-100 text-pink-800',
                                'Buleleng' => 'bg-cyan-100 text-cyan-800',
                                default => 'bg-gray-100 text-gray-800'
                            }
                        }}">
                            {{ $stat->region }}
                        </span>
                        <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total: <strong>{{ $stat->total }}</strong></span>
                        <span class="text-green-600">Active: <strong>{{ $stat->active }}</strong></span>
                        <span class="text-red-600">Issues: <strong>{{ $stat->problems }}</strong></span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Controls -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('fiber-cores.index') }}" class="flex flex-col lg:flex-row gap-4 items-center justify-between">
            <div class="flex flex-col md:flex-row gap-4 items-center">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                    <input
                        type="text"
                        name="search"
                        placeholder="Cari core, site, region, atau keterangan..."
                        class="pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-80"
                        value="{{ request('search') }}"
                    />
                </div>

                <div class="flex items-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4 text-gray-600"></i>
                    <select name="filter_status" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="All" {{ request('filter_status') === 'All' ? 'selected' : '' }}>Semua Status</option>
                        <option value="Active" {{ request('filter_status') === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('filter_status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>

                    <select name="filter_region" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="All" {{ request('filter_region') === 'All' ? 'selected' : '' }}>Semua Region</option>
                        @foreach($regions as $region)
                            <option value="{{ $region }}" {{ request('filter_region') === $region ? 'selected' : '' }}>
                                {{ $region }}
                            </option>
                        @endforeach
                    </select>
                </div> 

                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Filter
                </button>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('fiber-cores.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Tambah Core
                </a>
                @if($stats['total'] == 0)
                    <a href="{{ route('fiber-cores.generate-sample') }}"
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <i data-lucide="database" class="w-4 h-4"></i>
                        Generate Sample
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Fiber Core</h1>

    <table class="min-w-full border border-gray-300 rounded-lg overflow-hidden">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nama Site</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Region</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Jumlah Tube</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Jumlah Core</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($cores as $site)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $site->nama_site }}</td>
                    {{-- <td class="px-4 py-2">{{ $site->region }}</td> --}}
                    <td class="px-4 py-2">{{ $site->jumlah_tube }}</td>
                    <td class="px-4 py-2">{{ $site->jumlah_core }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('fiber-cores.show', $site->nama_site) }}"
                           class="text-blue-600 hover:underline">
                           Detail
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@ends$sites
     </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($cores->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $cores->links() }}
            </div>
        @endif
    </div>
@endsection