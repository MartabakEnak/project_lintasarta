@extends('layouts.app')

@section('title', 'Detail Core - Fiber Core Management')

@section('content')
    <div class="bg-white rounded-lg shadow-sm p-6 max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold flex items-center gap-2">
                <i data-lucide="server" class="w-7 h-7 text-blue-600"></i>
                Detail Core Fiber: <span class="text-blue-700">{{ $site->cable_id }}</span>
            </h2>
            <a href="{{ route('fiber-cores.index') }}"
               class="text-gray-600 hover:text-gray-800 flex items-center gap-2 border px-3 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-sm">
            <div><span class="font-semibold text-gray-700">Nama Site:</span> {{ $site->nama_site }}</div>
            <div><span class="font-semibold text-gray-700">Region:</span> {{ $site->region }}</div>
            <div><span class="font-semibold text-gray-700">Source Site:</span> {{ $site->source_site }}</div>
            <div><span class="font-semibold text-gray-700">Destination Site:</span> {{ $site->destination_site }}</div>
            <div><span class="font-semibold text-gray-700">Jumlah Tube:</span> {{ $cores->pluck('tube_number')->unique()->count() }}</div>
            <div><span class="font-semibold text-gray-700">Total Core:</span> {{ $cores->count() }}</div>

        </div>

        <h3 class="font-semibold mb-4 text-lg">Detail Core per Tube</h3>
        @php
            $tubes = $cores->groupBy('tube_number');
        @endphp

        @foreach($tubes as $tubeNumber => $tubeCores)
            <div class="mb-6 bg-gray-100 rounded-lg p-4 shadow-sm">
                <div class="mb-3 flex items-center gap-4">
                    <span class="font-bold text-base text-blue-800">Tube {{ $tubeNumber }}</span>
                    <span class="ml-2 text-sm text-gray-500">{{ $tubeCores->count() }} cores</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($tubeCores as $core)
                        <div class="bg-white rounded-lg shadow flex flex-col px-4 py-3 border border-gray-100 hover:shadow-lg transition">
                            <div class="flex items-center justify-between w-full mb-1">
                                <span class="font-semibold text-blue-700">Core {{ $core->core }}</span>
                                <span class="text-xs px-2 py-1 rounded-full
                                    {{ $core->status == 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $core->status }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs px-2 py-1 rounded-full
                                    {{ $core->penggunaan == 'OK' ? 'bg-green-50 text-green-700' : ($core->penggunaan == 'NOK' ? 'bg-red-50 text-red-700' : 'bg-yellow-50 text-yellow-700') }}">
                                    {{ $core->penggunaan }}
                                </span>
                            </div>
                            <div class="text-xs text-gray-500 mb-2">
                                <span class="font-medium text-gray-700">Keterangan:</span>
                                <span>{{ $core->keterangan ?: '-' }}</span>
                            </div>
                            <button
                                class="text-blue-600 hover:underline text-xs mt-auto self-start"
                                onclick="openEditModal('{{ $core->cable_id }}', {{ $core->id }}, '{{ $core->status }}', '{{ $core->penggunaan }}', `{{ addslashes($core->keterangan) }}`)">
                                <i data-lucide="edit-3" class="w-4 h-4 mr-1 inline"></i>Edit
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="fixed inset-0 z-50  items-center justify-center bg-black bg-opacity-30 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button onclick="closeEditModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <h3 class="text-lg font-bold mb-4">Edit Core</h3>
                <input type="hidden" name="core_id" id="edit_core_id">
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select name="status" id="edit_status" class="w-full border rounded px-2 py-1">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Penggunaan</label>
                    <select name="penggunaan" id="edit_penggunaan" class="w-full border rounded px-2 py-1">
                        <option value="OK">OK</option>
                        <option value="NOK">NOK</option>
                        <option value="Idle">Idle</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Keterangan</label>
                    <textarea name="keterangan" id="edit_keterangan" rows="3" class="w-full border rounded px-2 py-1"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="px-3 py-1 bg-gray-200 rounded">Batal</button>
                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openEditModal(cableId, id, status, penggunaan, keterangan) {
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');

        document.getElementById('edit_core_id').value = id;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_penggunaan').value = penggunaan;
        document.getElementById('edit_keterangan').value = keterangan;

        // Set action form ke route edit core
        document.getElementById('editForm').action = '/fiber-cores/' + cableId + '/' + id;
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');

    }
</script>
@endpush
