@extends('layouts.app')

@section('title', 'Edit Core - Fiber Core Management')

@section('content')
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold">Edit Core</h2>
                <a href="{{ route('fiber-cores.index') }}" 
                   class="text-gray-600 hover:text-gray-800 flex items-center gap-2">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali
                </a>
            </div>
            
            <form action="{{ route('fiber-cores.update', $fiberCore) }}" method="POST">
                @csrf
                @method('PUT')
                
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
                                value="{{ old('nama_site', $fiberCore->nama_site) }}"
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
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('region') border-red-500 @enderror"
                            >
                                <option value="">Pilih Region</option>
                                <option value="Denpasar Utara" {{ old('region', $fiberCore->region) == 'Denpasar Utara' ? 'selected' : '' }}>Denpasar Utara</option>
                                <option value="Denpasar Selatan" {{ old('region', $fiberCore->region) == 'Denpasar Selatan' ? 'selected' : '' }}>Denpasar Selatan</option>
                                <option value="Badung" {{ old('region', $fiberCore->region) == 'Badung' ? 'selected' : '' }}>Badung</option>
                                <option value="Gianyar" {{ old('region', $fiberCore->region) == 'Gianyar' ? 'selected' : '' }}>Gianyar</option>
                                <option value="Tabanan" {{ old('region', $fiberCore->region) == 'Tabanan' ? 'selected' : '' }}>Tabanan</option>
                                <option value="Klungkung" {{ old('region', $fiberCore->region) == 'Klungkung' ? 'selected' : '' }}>Klungkung</option>
                                <option value="Buleleng" {{ old('region', $fiberCore->region) == 'Buleleng' ? 'selected' : '' }}>Buleleng</option>
                                <option value="Bangli" {{ old('region', $fiberCore->region) == 'Bangli' ? 'selected' : '' }}>Bangli</option>
                                <option value="Jembrana" {{ old('region', $fiberCore->region) == 'Jembrana' ? 'selected' : '' }}>Jembrana</option>
                                <option value="Karangasem" {{ old('region', $fiberCore->region) == 'Karangasem' ? 'selected' : '' }}>Karangasem</option>
                            </select>
                            @error('region')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nomor Tube <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                name="tube_number"
                                required
                                min="1"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tube_number') border-red-500 @enderror"
                                value="{{ old('tube_number', $fiberCore->tube_number) }}"
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
                                Nomor Core (1-12) <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                name="core"
                                required
                                min="1"
                                max="12"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('core') border-red-500 @enderror"
                                value="{{ old('core', $fiberCore->core) }}"
                                placeholder="1-12"
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
                                <option value="Active" {{ old('status', $fiberCore->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status', $fiberCore->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
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
                                <option value="OK" {{ old('penggunaan', $fiberCore->penggunaan) == 'OK' ? 'selected' : '' }}>OK</option>
                                <option value="NOK" {{ old('penggunaan', $fiberCore->penggunaan) == 'NOK' ? 'selected' : '' }}>NOK</option>
                                <option value="Idle" {{ old('penggunaan', $fiberCore->penggunaan) == 'Idle' ? 'selected' : '' }}>Idle</option>
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
                                value="{{ old('otdr', $fiberCore->otdr) }}"
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
                                value="{{ old('source_site', $fiberCore->source_site) }}"
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
                                value="{{ old('destination_site', $fiberCore->destination_site) }}"
                                placeholder="Contoh: Sanur Beach"
                            />
                            @error('destination_site')
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
                        >{{ old('keterangan', $fiberCore->keterangan) }}</textarea>
                        <div class="text-xs text-gray-500 mt-1">
                            Contoh: "Dedicated untuk enterprise customer", "Problem di KM 15.2", "Backup untuk critical services", dll.
                        </div>
                        @error('keterangan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('fiber-cores.show', $fiberCore) }}" 
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