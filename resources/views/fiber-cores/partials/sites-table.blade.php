@foreach($sites as $site)
    <tr class="hover:bg-blue-50 transition-colors duration-200 group">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                    <i data-lucide="cable" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div>
                    <div class="text-sm font-bold text-blue-900">{{ $site->cable_id }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm font-medium text-gray-900">{{ $site->nama_site }}</div>
            <div class="text-xs text-gray-500 flex items-center mt-1">
                <i data-lucide="map-pin" class="w-3 h-3 mr-1"></i>
                {{ $site->source_site }} → {{ $site->destination_site }}
            </div>
        </td>
        <td class="px-6 py-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                <i data-lucide="map" class="w-3 h-3 mr-1"></i>
                {{ $site->region }}
            </span>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm text-gray-900 flex items-center">
                <i data-lucide="route" class="w-4 h-4 mr-2 text-gray-400"></i>
                {{ $site->source_site }} → {{ $site->destination_site }}
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm text-gray-900 font-mono">
                {{ number_format($site->otdr) }} m
            </div>
        </td>
        <td class="px-6 py-4">
            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                {{ $site->tube_number }} Tube
            </span>
        </td>
        <td class="px-6 py-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800">
                {{ number_format($site->total_core) }} Core
            </span>
        </td>
        <td class="px-6 py-4">
            <div class="flex items-center gap-2">
                <a href="{{ route('fiber-cores.show', $site->cable_id) }}"
                   class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-2 rounded-lg shadow transition-colors duration-200">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                    Detail
                </a>

                @if(Auth::user()->isSuperAdmin() || Auth::user()->canAccessRegion($site->region))
                    <button
                        onclick="confirmDelete('{{ $site->cable_id }}', '{{ $site->nama_site }}', {{ $site->total_core }})"
                        class="inline-flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-3 py-2 rounded-lg shadow transition-colors duration-200 group-hover:opacity-100">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Delete
                    </button>
                @endif
            </div>
        </td>
    </tr>
@endforeach

@if($sites->isEmpty())
    <tr>
        <td colspan="8" class="px-6 py-12 text-center">
            <div class="flex flex-col items-center">
                <div class="bg-gray-100 p-4 rounded-full mb-4">
                    <i data-lucide="search-x" class="w-8 h-8 text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data ditemukan</h3>
                <p class="text-gray-500 mb-4">Coba ubah kriteria pencarian Anda.</p>
                @if(request('search'))
                    <button onclick="clearAllFilters()" class="text-blue-600 hover:text-blue-700 font-medium">
                        Clear pencarian
                    </button>
                @endif
            </div>
        </td>
    </tr>
@endif
