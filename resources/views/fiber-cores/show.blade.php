@extends('layouts.app')

@section('title', 'Detail Core - Fiber Core Management')

@section('content')
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold">Detail Tube {{ $fiberCore->tube_number }} Core {{ $fiberCore->core }} - {{ $fiberCore->nama_site }}</h2>
                <div class="flex gap-2">
                    <a href="{{ route('fiber-cores.edit', $fiberCore) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Edit Core
                    </a>
                    <a href="{{ route('fiber-cores.index') }}" 
                       class="text-gray-600 hover:text-gray-800 flex items-center gap-2">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Kembali
                    </a>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-700 mb-2">Informasi Dasar</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Site:</span>
                                <span class="font-medium">{{ $fiberCore->nama_site }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Region:</span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $fiberCore->region_badge }}">
                                    {{ $fiberCore->region }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tube:</span>
                                <span class="font-medium">Tube {{ $fiberCore->tube_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Core:</span>
                                <span class="font-medium">{{ $fiberCore->core }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tube Label:</span>
                                <span class="font-medium">{{ $fiberCore->tube }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">OTDR:</span>
                                <span class="font-medium">{{ number_format($fiberCore->otdr) }} m</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-700 mb-2">Status</h3>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                @if($fiberCore->status === 'Active' && $fiberCore->penggunaan === 'OK')
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                                @elseif($fiberCore->status === 'Active' && $fiberCore->penggunaan === 'NOK')
                                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i>
                                @else
                                    <i data-lucide="x-circle" class="w-5 h-5 text-gray-500"></i>
                                @endif
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $fiberCore->status_badge }}">
                                    {{ $fiberCore->status }}
                                </span>
                            </div>
                            <div>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $fiberCore->penggunaan_badge }}">
                                    {{ $fiberCore->penggunaan }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-700 mb-2">Routing</h3>
                        <div class="text-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="font-medium">{{ $fiberCore->source_site }}</span>
                            </div>
                            <div class="flex items-center gap-2 ml-1">
                                <div class="w-1 h-6 bg-gray-300"></div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="font-medium">{{ $fiberCore->destination_site }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-700 mb-2">Performance</h3>
                        <div class="text-sm space-y-1">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Distance:</span>
                                <span class="font-medium">{{ number_format($fiberCore->otdr) }} m</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Signal Quality:</span>
                                <span class="font-medium {{ $fiberCore->penggunaan === 'OK' ? 'text-green-600' : ($fiberCore->penggunaan === 'NOK' ? 'text-red-600' : 'text-yellow-600') }}">
                                    {{ $fiberCore->penggunaan === 'OK' ? 'Good' : ($fiberCore->penggunaan === 'NOK' ? 'Poor' : 'Idle') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Created:</span>
                                <span class="font-medium">{{ $fiberCore->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Updated:</span>
                                <span class="font-medium">{{ $fiberCore->updated_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($fiberCore->keterangan)
                <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-700 mb-2">Keterangan Lengkap</h3>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        {{ $fiberCore->keterangan }}
                    </p>
                </div>
            @endif

            <div class="flex justify-end gap-3 pt-4 border-t mt-6">
                <a href="{{ route('fiber-cores.index') }}" 
                   class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Kembali ke Daftar
                </a>
                <a href="{{ route('fiber-cores.edit', $fiberCore) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Edit Core
                </a>
            </div>
        </div>
    </div>
@endsection