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
            @if($stats['total'] == 0 && Auth::user()->isSuperAdmin())
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
                <p class="text-3xl font-bold text-white">{{ number_format($stats['total']) }}</p>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-xl shadow-lg p-6 flex items-center gap-4">
            <div class="flex-shrink-0 bg-white bg-opacity-20 rounded-full p-3">
                <i data-lucide="check-circle" class="w-8 h-8 text-white"></i>
            </div>
            <div>
                <p class="text-sm text-green-100">Core Active</p>
                <p class="text-3xl font-bold text-white">{{ number_format($stats['active']) }}</p>
            </div>
        </div>
        <div class="bg-gradient-to-br from-gray-400 to-gray-600 rounded-xl shadow-lg p-6 flex items-center gap-4">
            <div class="flex-shrink-0 bg-white bg-opacity-20 rounded-full p-3">
                <i data-lucide="x-circle" class="w-8 h-8 text-white"></i>
            </div>
            <div>
                <p class="text-sm text-gray-100">Core Inactive</p>
                <p class="text-3xl font-bold text-white">{{ number_format($stats['inactive']) }}</p>
            </div>
        </div>
        <div class="bg-gradient-to-br from-red-400 to-red-600 rounded-xl shadow-lg p-6 flex items-center gap-4">
            <div class="flex-shrink-0 bg-white bg-opacity-20 rounded-full p-3">
                <i data-lucide="alert-triangle" class="w-8 h-8 text-white"></i>
            </div>
            <div>
                <p class="text-sm text-red-100">Problem</p>
                <p class="text-3xl font-bold text-white">{{ number_format($stats['problems']) }}</p>
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
                        <span class="text-gray-700">Total: <strong>{{ number_format($stat->total) }}</strong></span>
                        <span class="text-green-700">Active: <strong>{{ number_format($stat->active) }}</strong></span>
                        <span class="text-red-700">Issues: <strong>{{ number_format($stat->problems) }}</strong></span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Search Controls -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-10">
        <div class="flex flex-col lg:flex-row gap-6 items-center justify-between">
            <div class="flex flex-col md:flex-row gap-4 items-center w-full">
                <div class="relative flex-1 max-w-md">
                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"></i>
                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Cari cable ID, site, region, atau route..."
                        class="w-full pl-12 pr-4 py-3 border-2 border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow"
                        value="{{ request('search') }}"
                    />
                    <div id="searchLoading" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                    </div>
                </div>

                <button id="clearFilters" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-3 rounded-lg font-semibold shadow transition">
                    <i data-lucide="x" class="w-5 h-5 mr-1"></i> Clear
                </button>
            </div>
        </div>

        <!-- Search Results Count -->
        <div id="searchResults" class="mt-4 text-sm text-gray-600 hidden">
            <span id="resultsCount"></span>
        </div>
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
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">OTDR (m)</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Jumlah Tube</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Total Core</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="sitesTableBody" class="bg-white divide-y divide-blue-100">
                    @include('fiber-cores.partials.sites-table', ['sites' => $sites])
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-red-100 p-2 rounded-full">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-red-600"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Konfirmasi Hapus Cable</h3>
            </div>

            <div class="mb-6">
                <p class="text-gray-600 mb-3">Apakah Anda yakin ingin menghapus cable berikut?</p>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="space-y-2">
                        <div><span class="font-semibold">Cable ID:</span> <span id="deleteCableId" class="text-red-700 font-medium"></span></div>
                        <div><span class="font-semibold">Nama Site:</span> <span id="deleteSiteName" class="text-red-700"></span></div>
                        <div><span class="font-semibold">Total Core:</span> <span id="deleteTotalCore" class="text-red-700 font-medium"></span> core akan dihapus</div>
                    </div>
                </div>
                <p class="text-red-600 text-sm mt-3 font-medium">⚠️ Tindakan ini tidak dapat dibatalkan!</p>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        Ya, Hapus Cable
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');
        const searchInput = document.getElementById('searchInput');
        const searchLoading = document.getElementById('searchLoading');
        const searchIndicator = document.getElementById('searchIndicator');
        const clearSearchBtn = document.getElementById('clearSearch');

        let searchTimeout;

        // Function to show loading state
        function showLoading() {
            searchLoading?.classList.remove('hidden');
            searchIndicator?.classList.add('animate-pulse', 'bg-blue-200');
            searchIndicator?.classList.remove('bg-blue-100');
        }

        // Function to hide loading state
        function hideLoading() {
            searchLoading?.classList.add('hidden');
            searchIndicator?.classList.remove('animate-pulse', 'bg-blue-200');
            searchIndicator?.classList.add('bg-blue-100');
        }

        // Function to submit form with delay (debounce)
        function submitFormWithDelay() {
            clearTimeout(searchTimeout);
            showLoading();

            searchTimeout = setTimeout(function() {
                searchForm.submit();
            }, 600); // 600ms delay for better UX
        }

        // Real-time search on input
        searchInput?.addEventListener('input', function() {
            submitFormWithDelay();
        });

        // Clear search functionality
        clearSearchBtn?.addEventListener('click', function() {
            searchInput.value = '';
            submitFormWithDelay();
        });

        // Clear search on Escape key
        searchInput?.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchInput.value = '';
                submitFormWithDelay();
            }
        });

        // Visual feedback on focus
        searchInput?.addEventListener('focus', function() {
            searchIndicator?.classList.add('ring-2', 'ring-blue-300', 'ring-opacity-50');
        });

        searchInput?.addEventListener('blur', function() {
            searchIndicator?.classList.remove('ring-2', 'ring-blue-300', 'ring-opacity-50');
        });

        // Prevent form submission on Enter if search is empty
        searchForm?.addEventListener('submit', function(e) {
            if (searchInput.value.trim() === '') {
                e.preventDefault();
                window.location.href = "{{ route('fiber-cores.index') }}";
            }
        });

        // Auto-focus search input on page load if there's a search query
        @if(request('search'))
        searchInput?.focus();
        searchInput?.setSelectionRange(searchInput.value.length, searchInput.value.length);
        @endif

        // Show success message for search results
        @if(session('search_success'))
        setTimeout(() => {
            const alert = document.createElement('div');
            alert.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
            alert.innerHTML = `
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    <span>{{ session('search_success') }}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-green-500 hover:text-green-700">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            `;
            document.body.appendChild(alert);

            // Auto remove after 5 seconds
            setTimeout(() => {
                alert?.remove();
            }, 5000);
        }, 100);
        @endif
    });

    // Delete Modal Functions
    function confirmDelete(cableId, siteName, totalCore) {
        document.getElementById('deleteCableId').textContent = cableId;
        document.getElementById('deleteSiteName').textContent = siteName;
        document.getElementById('deleteTotalCore').textContent = totalCore;

        // Set form action
        document.getElementById('deleteForm').action = `/fiber-cores/delete-cable/${cableId}`;

        // Show modal
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });

    // Global function to clear all filters (used in sites-table partial)
    window.clearAllFilters = function() {
        searchInput.value = '';
        searchResults.classList.add('hidden');
        performSearch();
    };

    // Function to reinitialize Lucide icons after AJAX load
    window.reinitializeIcons = function() {
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }
    };
    </script>
    @endpush
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearFilters = document.getElementById('clearFilters');
    const searchLoading = document.getElementById('searchLoading');
    const searchResults = document.getElementById('searchResults');
    const resultsCount = document.getElementById('resultsCount');
    const sitesTableBody = document.getElementById('sitesTableBody');

    let searchTimeout;

    // Real-time search function
    function performSearch() {
        const search = searchInput.value;

        // Show loading
        searchLoading.classList.remove('hidden');

        // Build URL with parameters
        const params = new URLSearchParams();
        if (search) params.append('search', search);

        // Make AJAX request
        fetch(`{{ route('fiber-cores.search') }}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update table content
            sitesTableBody.innerHTML = data.html;

            // Update results count
            if (search) {
                searchResults.classList.remove('hidden');
                resultsCount.textContent = `Menampilkan ${data.count} hasil`;
            } else {
                searchResults.classList.add('hidden');
            }

            // Reinitialize icons
            reinitializeIcons();
        })
        .catch(error => {
            console.error('Search error:', error);
        })
        .finally(() => {
            // Hide loading
            searchLoading.classList.add('hidden');
        });
    }

    // Search input with debounce
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 300); // 300ms delay
    });

    // Clear filters
    clearFilters.addEventListener('click', function() {
        searchInput.value = '';
        searchResults.classList.add('hidden');
        performSearch();
    });

    // Initial search if there are URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search')) {
        performSearch();
    }

    // Make performSearch globally accessible
    window.performSearch = performSearch;
});
</script>
@endpush
