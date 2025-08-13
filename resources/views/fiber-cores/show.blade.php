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
            <div><span class="font-semibold text-gray-700">OTDR:</span>{{ $site->otdr}} m</div>
        </div>

        <h3 class="font-semibold mb-4 text-lg">Detail Core per Tube</h3>
        @php
            $tubes = $cores->groupBy('tube_number');
        @endphp

        @php
            // 12 warna fiber optik standar (bisa diganti sesuai kebutuhan)
            $fiberColors = [
                '#ff0000', // Merah
                '#0000ff', // Biru
                '#00ff00', // Hijau
                '#ffff00', // Kuning
                '#ff8000', // Orange
                '#800080', // Ungu
                '#00ffff', // Cyan
                '#ff00ff', // Magenta
                '#964B00', // Coklat
                '#808080', // Abu-abu
                '#000000', // Hitam
                '#ffffff', // Putih
            ];
        @endphp

        @foreach($tubes as $tubeNumber => $tubeCores)
            <div class="mb-6 bg-gray-100 rounded-lg p-4 shadow-sm">
                <div class="mb-3 flex items-center gap-4">
                    <span class="font-bold text-base text-blue-800">Tube {{ $tubeNumber }}</span>
                    <span class="ml-2 text-sm text-gray-500">{{ $tubeCores->count() }} cores</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-3">
                    @foreach($tubeCores->sortBy('core') as $index => $core)
                        @php
                            // Menggunakan index loop untuk menentukan warna, bukan nomor core
                            // Index dimulai dari 0, jadi langsung digunakan
                            $colorIndex = $index % 12;
                            $coreColor = $fiberColors[$colorIndex];
                        @endphp
                        <div class="bg-white rounded-lg shadow-sm flex flex-col px-3 py-2 border border-gray-200 hover:shadow-md transition-all duration-200 relative group cursor-pointer">
                            <!-- Core Info -->
                            <div class="flex items-center justify-between w-full mb-2">
                                <span class="font-semibold text-blue-700 text-sm">Core {{ $core->core }}</span>
                                <span class="inline-block w-4 h-4 rounded-full border border-gray-300"
                                      style="background: {{ $coreColor }};"
                                      title="Warna Fiber"></span>
                            </div>

                            <!-- Edit Button - Always Visible -->
                            <button
                                class="text-blue-600 hover:text-blue-800 text-xs flex items-center gap-1 mt-auto"
                                onclick="openEditModal('{{ $core->cable_id }}', {{ $core->id }}, '{{ $core->status }}', '{{ $core->penggunaan }}', `{{ addslashes($core->keterangan) }}`)">
                                <i data-lucide="edit-3" class="w-3 h-3"></i>
                                Edit
                            </button>

                            <!-- Tooltip Popup - Hidden by default, shown on hover -->
                            <div class="absolute left-1/2 bottom-full mb-2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded-lg px-3 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10 whitespace-nowrap shadow-lg">
                                <!-- Arrow pointing down -->
                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>

                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold">Status:</span>
                                        <span class="px-2 py-1 rounded-full text-xs
                                            {{ $core->status == 'Active' ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' }}">
                                            {{ $core->status }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold">Penggunaan:</span>
                                        <span class="px-2 py-1 rounded-full text-xs
                                            {{ $core->penggunaan == 'OK' ? 'bg-green-500 text-white' : ($core->penggunaan == 'NOK' ? 'bg-red-500 text-white' : 'bg-yellow-500 text-white') }}">
                                            {{ $core->penggunaan }}
                                        </span>
                                    </div>
                                    @if($core->keterangan)
                                        <div>
                                            <span class="font-semibold">Keterangan:</span>
                                            <div class="text-gray-200">{{ $core->keterangan }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-30 hidden">
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
