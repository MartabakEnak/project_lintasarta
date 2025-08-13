@extends('layouts.app')

@section('title', 'Dashboard - Fiber Core Management')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-700 via-blue-500 to-blue-400 rounded-xl shadow-lg p-8 mb-10 overflow-hidden">
        <div class="absolute right-0 top-0 opacity-20 pointer-events-none">
            <i data-lucide="activity" class="w-64 h-64 text-white"></i>
        </div>
        <h1 class="text-4xl font-extrabold text-white mb-2 drop-shadow-lg">
            Sistem Manajemen Core Fiber Optik
        </h1>
        <p class="text-lg text-blue-100 mb-4">Monitoring, statistik, dan pengelolaan core fiber optik Anda dalam satu dashboard.</p>
        <div class="flex gap-4">
            <a href="{{ route('fiber-cores.create') }}"
               class="inline-flex items-center gap-2 bg-white text-blue-700 font-semibold px-6 py-3 rounded-lg shadow hover:bg-blue-50 transition">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Tambah Core
            </a>
            @if($stats['total'] == 0)
                <a href="{{ route('fiber-cores.generate-sample') }}"
                   class="inline-flex items-center gap-2 bg-green-600 text-white font-semibold px-6 py-3 rounded-lg shadow hover:bg-green-700 transition">
                    <i data-lucide="database" class="w-5 h-5"></i>
                    Generate Sample
                </a>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow-lg p-6 flex items-center gap-4">
            <div class="flex-shrink-0 bg-white bg-opacity-20 rounded-full p-3">
                <i data-lucide="layers" class="w-8 h-8 text-white"></i>
            </div>
            <div>
                <p class="text-sm text-blue-100">Total Core</p>
                <p class="text-3xl font-bold text-white">{{ $stats['total'] }}</p>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-xl shadow-lg p-6 flex items-center gap-4">
            <div class="flex-shrink-0 bg-white bg-opacity-20 rounded-full p-3">
                <i data-lucide="check-circle" class="w-8 h-8 text-white"></i>
            </div>
            <div>
                <p class="text-sm text-green-100">Core Active</p>
                <p class="text-3xl font-bold text-white">{{ $stats['active'] }}</p>
            </div>
        </div>
        <div class="bg-gradient-to-br from-gray-400 to-gray-600 rounded-xl shadow-lg p-6 flex items-center gap-4">
            <div class="flex-shrink-0 bg-white bg-opacity-20 rounded-full p-3">
                <i data-lucide="x-circle" class="w-8 h-8 text-white"></i>
            </div>
            <div>
                <p class="text-sm text-gray-100">Core Inactive</p>
                <p class="text-3xl font-bold text-white">{{ $stats['inactive'] }}</p>
            </div>
        </div>
        <div class="bg-gradient-to-br from-red-400 to-red-600 rounded-xl shadow-lg p-6 flex items-center gap-4">
            <div class="flex-shrink-0 bg-white bg-opacity-20 rounded-full p-3">
                <i data-lucide="alert-triangle" class="w-8 h-8 text-white"></i>
            </div>
            <div>
                <p class="text-sm text-red-100">Problem</p>
                <p class="text-3xl font-bold text-white">{{ $stats['problems'] }}</p>
            </div>
        </div>
    </div>

    <!-- Regional Overview -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-10">
        <h2 class="text-2xl font-bold text-blue-700 mb-6 flex items-center gap-2">
            <i data-lucide="map-pin" class="w-6 h-6"></i>
            Overview Regional
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($regionalStats as $stat)
                <div class="bg-gradient-to-br from-blue-50 to-white rounded-lg p-6 shadow flex flex-col gap-2 border border-blue-100">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 shadow">
                            {{ $stat->region }}
                        </span>
                        <i data-lucide="map-pin" class="w-4 h-4 text-blue-400"></i>
                    </div>
                    <div class="flex flex-col gap-1 text-sm">
                        <span class="text-gray-700">Total: <strong>{{ $stat->total }}</strong></span>
                        <span class="text-green-700">Active: <strong>{{ $stat->active }}</strong></span>
                        <span class="text-red-700">Issues: <strong>{{ $stat->problems }}</strong></span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Controls -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-10">
        <form method="GET" action="{{ route('fiber-cores.index') }}" class="flex flex-col lg:flex-row gap-6 items-center justify-between">
            <div class="flex flex-col md:flex-row gap-4 items-center">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"></i>
                    <input
                        type="text"
                        name="search"
                        placeholder="Cari core, site, region, atau keterangan..."
                        class="pl-12 pr-4 py-3 border-2 border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-80 shadow"
                        value="{{ request('search') }}"
                    />
                </div>

                <div class="flex items-center gap-2">
                    <i data-lucide="filter" class="w-5 h-5 text-blue-600"></i>
                    <select name="filter_status" class="border-2 border-blue-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="All" {{ request('filter_status') === 'All' ? 'selected' : '' }}>Semua Status</option>
                        <option value="Active" {{ request('filter_status') === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('filter_status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>

                    <select name="filter_region" class="border-2 border-blue-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="All" {{ request('filter_region') === 'All' ? 'selected' : '' }}>Semua Region</option>
                        @foreach($regions as $region)
                            <option value="{{ $region }}" {{ request('filter_region') === $region ? 'selected' : '' }}>
                                {{ $region }}
                            </option>
                        @endforeach
                    </select>
                </div> 

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold shadow transition">
                    <i data-lucide="filter" class="w-5 h-5 mr-1"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-100 to-blue-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Cable ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Site</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Region</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Route</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Jumlah Tube</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Total Core</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-blue-100">
                    @forelse($sites as $site)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-blue-900">
                                {{ $site->cable_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-blue-900">
                                {{ $site->nama_site }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 shadow">
                                    {{ $site->region }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-blue-900">{{ $site->source_site }}</div>
                                <div class="text-xs text-blue-400">↓</div>
                                <div class="text-sm text-blue-900">{{ $site->destination_site }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900">
                                {{ $site->tube_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900">
                                {{ $site->total_core }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('fiber-cores.show', $site->cable_id) }}"
                                   class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition"
                                   title="Lihat Detail">
                                    <i data-lucide="search" class="w-4 h-4"></i>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-gray-500">
                                Tidak ada data site.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($sites, 'links'))
            <div class="bg-white px-4 py-3 border-t border-blue-100 sm:px-6">
                {{ $sites->links() }}
            </div>
        @endif
    </div>
@endsection