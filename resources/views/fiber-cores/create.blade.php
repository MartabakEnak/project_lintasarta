@extends('layouts.app')

@section('title', 'Tambah Core Baru - Fiber Core Management')

@section('content')

    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold">Tambah Core Baru</h2>
                <a href="{{ route('fiber-cores.index') }}"
                   class="text-gray-600 hover:text-gray-800 flex items-center gap-2">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali
                </a>
            </div>

            <form action="{{ route('fiber-cores.store') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Site <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="nama_site"
                                required
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_site') border-red-500 @enderror"
                                value="{{ old('nama_site') }}"
                                placeholder="Contoh: Teuku Umar"
                            />
                            @error('nama_site')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Region <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="region"
                            required
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('region') border-red-500 @enderror">
                            <option value="">Pilih Region</option>
                            <option value="Bali" {{ old('region') == 'Bali' ? 'selected' : '' }}>Bali</option>
                            <option value="Nusa Tenggara Barat" {{ old('region') == 'Nusa Tenggara Barat' ? 'selected' : '' }}>Nusa Tenggara Barat</option>
                            <option value="Nusa Tenggara Timur" {{ old('region') == 'Nusa Tenggara Timur' ? 'selected' : '' }}>Nusa Tenggara Timur</option>
                        </select>

                        @error('region')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jumlah Tube <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="tube_number"
                                name="tube_number"
                                required
                                min="1"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tube_number') border-red-500 @enderror"
                                value="{{ old('tube_number') }}"
                                placeholder="Contoh: 2"
                            />
                            <div class="text-xs text-gray-500 mt-1">
                                Setiap tube memiliki 12 core (1-12)
                            </div>
                            @error('tube_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jumlah Core <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="core"
                                name="core"
                                required
                                min="1"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('core') border-red-500 @enderror"
                                value="{{ old('core') }}"
                                placeholder="Contoh: 24"
                            />
                            <div class="text-xs text-gray-500 mt-1">
                                Core dalam tube (maksimal 12 per tube)
                            </div>
                            @error('core')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="status"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror"
                            >
                                <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Status Penggunaan <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="penggunaan"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('penggunaan') border-red-500 @enderror"
                            >
                                <option value="OK" {{ old('penggunaan', 'OK') == 'OK' ? 'selected' : '' }}>OK</option>
                                <option value="NOK" {{ old('penggunaan') == 'NOK' ? 'selected' : '' }}>NOK</option>
                                <option value="Idle" {{ old('penggunaan') == 'Idle' ? 'selected' : '' }}>Idle</option>
                            </select>
                            @error('penggunaan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                OTDR (m) <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                name="otdr"
                                required
                                min="0"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('otdr') border-red-500 @enderror"
                                value="{{ old('otdr') }}"
                                placeholder="Contoh: 1500"
                            />
                            @error('otdr')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Source Site <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="source_site"
                                required
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('source_site') border-red-500 @enderror"
                                value="{{ old('source_site') }}"
                                placeholder="Contoh: Teuku Umar"
                            />
                            @error('source_site')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Destination Site <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="destination_site"
                                required
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('destination_site') border-red-500 @enderror"
                                value="{{ old('destination_site') }}"
                                placeholder="Contoh: Sanur Beach"
                            />
                            @error('destination_site')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Cable ID <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="cable_id"
                                required
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cable_id') border-red-500 @enderror"
                                value="{{ old('cable_id') }}"
                                placeholder="Contoh: CBL-001"
                            />
                            @error('cable_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Keterangan Detail
                        </label>
                        <textarea
                            name="keterangan"
                            rows="4"
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-500 @enderror"
                            placeholder="Masukkan keterangan detail tentang fungsi, kondisi, atau catatan khusus untuk core ini..."
                        >{{ old('keterangan') }}</textarea>
                        <div class="text-xs text-gray-500 mt-1">
                            Contoh: "Dedicated untuk enterprise customer", "Problem di KM 15.2", "Backup untuk critical services", dll.
                        </div>
                        @error('keterangan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="tube-core-preview" class="mt-4 p-3 bg-gray-50 rounded text-sm text-gray-700"></div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('fiber-cores.index') }}"
                           class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </a>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tubeInput = document.getElementById('tube_number');
    const coreInput = document.getElementById('core');
    const preview = document.getElementById('tube-core-preview');

    function updatePreview() {
        const tube = parseInt(tubeInput.value) || 0;
        const core = parseInt(coreInput.value) || 0;
        if (tube > 0 && core > 0) {
            let html = '<b>Distribusi Tube-Core:</b><br>';
            let corePerTube = Math.floor(core / tube);
            let sisa = core % tube;
            let currentCore = 1;
            for (let t = 1; t <= tube; t++) {
                let jumlahCore = corePerTube + (t <= sisa ? 1 : 0);
                for (let c = 1; c <= jumlahCore; c++) {
                    html += `tube${t}-core${currentCore}<br>`;
                    currentCore++;
                }
            }
            preview.innerHTML = html;
        } else {
            preview.innerHTML = '';
        }
    }

    tubeInput.addEventListener('input', updatePreview);
    coreInput.addEventListener('input', updatePreview);

    updatePreview();
});
</script>
@endpush
