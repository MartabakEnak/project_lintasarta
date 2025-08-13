{{-- resources/views/fiber-cores/partials/sites-table.blade.php --}}
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
            {{ $site->otdr }}
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
            <div class="flex flex-col items-center gap-2">
                <i data-lucide="search-x" class="w-12 h-12 text-gray-300"></i>
                <p>Tidak ada data yang ditemukan</p>
                <p class="text-sm">Coba ubah kata kunci pencarian atau filter</p>
            </div>
        </td>
    </tr>
@endforelse