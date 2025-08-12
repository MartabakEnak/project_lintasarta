@extends('layouts.app')

@section('title', 'Detail Core - Fiber Core Management')

@section('content')
    <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold">Detail Core Fiber</h2>
            <a href="{{ route('fiber-cores.index') }}"
               class="text-gray-600 hover:text-gray-800 flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <div>
                <span class="font-semibold text-gray-700">Nama Site:</span>
                <span class="ml-2">{{ $core->nama_site }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Region:</span>
                <span class="ml-2">{{ $core->region }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Tube:</span>
                <span class="ml-2">{{ $core->tube }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Nomor Core:</span>
                <span class="ml-2">{{ $core->core }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Status:</span>
                <span class="ml-2">{{ $core->status }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Status Penggunaan:</span>
                <span class="ml-2">{{ $core->penggunaan }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">OTDR (m):</span>
                <span class="ml-2">{{ number_format($core->otdr) }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Source Site:</span>
                <span class="ml-2">{{ $core->source_site }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Destination Site:</span>
                <span class="ml-2">{{ $core->destination_site }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Keterangan:</span>
                <div class="ml-2 text-gray-800 whitespace-pre-line">{{ $core->keterangan }}</div>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-6">
            <a href="{{ route('fiber-cores.edit', $core) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <i data-lucide="edit-3" class="w-4 h-4"></i>
                Edit
            </a>
            <form action="{{ route('fiber-cores.destroy', $core) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    Hapus
                </button>
            </form>
        </div>
    </div>
@endsection
