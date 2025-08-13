<?php

namespace App\Http\Controllers;

use App\Models\FiberCore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FiberCoreController extends Controller
{
    // Constructor tidak diperlukan, kita akan menggunakan middleware di routes

    /**
     * Display a listing of fiber cores
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Base query
        $query = DB::table('fiber_cores')
            ->select(
                'cable_id',
                'nama_site',
                'region',
                'source_site',
                'destination_site',
                DB::raw('MAX(tube_number) as tube_number'),
                DB::raw('COUNT(*) as total_core')
            );

        // Apply regional filter for non-superadmin users
        if (!$user->isSuperAdmin()) {
            $query->where('region', $user->region);
        }

        // Apply search filter if provided
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('cable_id', 'like', "%{$searchTerm}%")
                    ->orWhere('nama_site', 'like', "%{$searchTerm}%")
                    ->orWhere('region', 'like', "%{$searchTerm}%")
                    ->orWhere('source_site', 'like', "%{$searchTerm}%")
                    ->orWhere('destination_site', 'like', "%{$searchTerm}%");
            });
        }

        // Apply region filter
        if ($request->has('filter_region') && $request->filter_region && $request->filter_region !== 'All') {
            $query->where('region', $request->filter_region);
        }

        $sites = $query->groupBy('cable_id', 'nama_site', 'region', 'source_site', 'destination_site')
            ->orderBy('cable_id')
            ->get();

        // Statistics with regional filtering - fix the query issue
        $baseQuery = FiberCore::query();
        if (!$user->isSuperAdmin()) {
            $baseQuery->where('region', $user->region);
        }

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'active' => (clone $baseQuery)->where('status', 'Active')->count(),
            'inactive' => (clone $baseQuery)->where('status', 'Inactive')->count(),
            'problems' => (clone $baseQuery)->where('penggunaan', 'NOK')->count(),
        ];

        // Regional statistics
        $regionalStatsQuery = DB::table('fiber_cores')
            ->select(
                'region',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "Active" THEN 1 ELSE 0 END) as active'),
                DB::raw('SUM(CASE WHEN penggunaan = "NOK" THEN 1 ELSE 0 END) as problems')
            );

        if (!$user->isSuperAdmin()) {
            $regionalStatsQuery->where('region', $user->region);
        }

        $regionalStats = $regionalStatsQuery->groupBy('region')
            ->orderBy('region')
            ->get();

        // Get regions based on user access
        $regions = $user->isSuperAdmin()
            ? FiberCore::select('region')->distinct()->pluck('region')
            : collect([$user->region]);

        return view('fiber-cores.index', compact('sites', 'stats', 'regionalStats', 'regions'));
    }

    /**
     * Show the form for creating a new fiber core
     */
    public function create()
    {
        return view('fiber-cores.create');
    }

    /**
     * Store a newly created fiber core
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'cable_id' => 'required|string|max:255',
            'nama_site' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'tube_number' => 'required|integer|min:1',
            'core' => 'required|integer|min:1',
            'status' => 'required|in:Active,Inactive',
            'penggunaan' => 'required|in:OK,NOK,Idle',
            'otdr' => 'required|integer|min:0',
            'source_site' => 'required|string|max:255',
            'destination_site' => 'required|string|max:255',
            'keterangan' => 'nullable|string'
        ]);

        // Check if regional user is trying to create in their region
        if (!$user->isSuperAdmin() && !$user->canAccessRegion($validated['region'])) {
            return redirect()->back()
                ->with('error', 'Anda hanya dapat membuat core di region Anda.')
                ->withInput();
        }

        $tube = (int) $validated['tube_number'];
        $core = (int) $validated['core'];
        $corePerTube = floor($core / $tube);
        $sisa = $core % $tube;
        $currentCore = 1;

        for ($t = 1; $t <= $tube; $t++) {
            $jumlahCore = $corePerTube + ($t <= $sisa ? 1 : 0);
            for ($c = 1; $c <= $jumlahCore; $c++) {
                // Cek duplikat core pada cable_id
                $exists = FiberCore::where('cable_id', $validated['cable_id'])
                    ->where('tube_number', $t)
                    ->where('core', $currentCore)
                    ->exists();
                if (!$exists) {
                    FiberCore::create([
                        'cable_id' => $validated['cable_id'],
                        'nama_site' => $validated['nama_site'],
                        'region' => $validated['region'],
                        'tube_number' => $t,
                        'core' => $currentCore,
                        'status' => $validated['status'],
                        'penggunaan' => $validated['penggunaan'],
                        'otdr' => $validated['otdr'],
                        'source_site' => $validated['source_site'],
                        'destination_site' => $validated['destination_site'],
                        'keterangan' => $validated['keterangan'],
                    ]);
                }
                $currentCore++;
            }
        }

        return redirect()->route('fiber-cores.index')
            ->with('success', 'Semua core berhasil ditambahkan!');
    }

    /**
     * Display the specified fiber core
     */
    public function show($cable_id)
    {
        $user = Auth::user();

        // Ambil semua core dengan cable_id ini
        $cores = FiberCore::where('cable_id', $cable_id)
            ->orderBy('tube_number')
            ->orderBy('core')
            ->get();

        if ($cores->isEmpty()) {
            return redirect()->route('fiber-cores.index')
                ->with('error', 'Cable ID tidak ditemukan!');
        }

        // Ambil info site (ambil satu saja)
        $site = $cores->first();

        // Check regional access
        if (!$user->isSuperAdmin() && !$user->canAccessRegion($site->region)) {
            abort(403, 'Anda tidak memiliki akses ke region ini.');
        }

        return view('fiber-cores.show', compact('site', 'cores'));
    }

    /**
     * Show the form for editing the specified fiber core
     */
    public function edit(FiberCore $fiberCore)
    {
        $user = Auth::user();

        // Check regional access
        if (!$user->isSuperAdmin() && !$user->canAccessRegion($fiberCore->region)) {
            abort(403, 'Anda tidak memiliki akses ke region ini.');
        }

        return view('fiber-cores.edit', compact('fiberCore'));
    }

    /**
     * Update the specified fiber core
     */
    public function update(Request $request, $cable_id, $id)
    {
        $user = Auth::user();

        $core = FiberCore::where('cable_id', $cable_id)->where('id', $id)->firstOrFail();

        // Check regional access
        if (!$user->isSuperAdmin() && !$user->canAccessRegion($core->region)) {
            abort(403, 'Anda tidak memiliki akses ke region ini.');
        }

        $validated = $request->validate([
            'status' => 'required|in:Active,Inactive',
            'penggunaan' => 'required|in:OK,NOK,Idle',
            'keterangan' => 'nullable|string'
        ]);

        $core->update($validated);

        return redirect()->route('fiber-cores.show', $cable_id)
            ->with('success', 'Core berhasil diperbarui!');
    }

    /**
     * Remove the specified fiber core
     */
    public function destroy(FiberCore $fiberCore)
    {
        $user = Auth::user();

        // Check regional access
        if (!$user->isSuperAdmin() && !$user->canAccessRegion($fiberCore->region)) {
            abort(403, 'Anda tidak memiliki akses ke region ini.');
        }

        $fiberCore->delete();

        return redirect()->route('fiber-cores.index')
            ->with('success', 'Core fiber berhasil dihapus!');
    }

    /**
     * Generate sample data (for testing purposes)
     */
    public function generateSample()
    {
        $user = Auth::user();

        // Only superadmin can generate sample data
        if (!$user->isSuperAdmin()) {
            return redirect()->route('fiber-cores.index')
                ->with('error', 'Hanya superadmin yang dapat generate sample data.');
        }

        $sites = [
            ['name' => 'Teuku Umar', 'region' => 'Denpasar Utara', 'cable_id' => 'CBL-001'],
            ['name' => 'Sanur Beach', 'region' => 'Denpasar Selatan', 'cable_id' => 'CBL-002'],
            ['name' => 'Ubud Central', 'region' => 'Gianyar', 'cable_id' => 'CBL-003'],
            ['name' => 'Kuta Square', 'region' => 'Badung', 'cable_id' => 'CBL-004'],
            ['name' => 'Ngurah Rai Airport', 'region' => 'Badung', 'cable_id' => 'CBL-005'],
        ];

        foreach ($sites as $index => $site) {
            for ($tube = 1; $tube <= 2; $tube++) {
                for ($core = 1; $core <= 12; $core++) {
                    FiberCore::create([
                        'cable_id' => $site['cable_id'],
                        'nama_site' => $site['name'],
                        'region' => $site['region'],
                        'tube_number' => $tube,
                        'core' => $core,
                        'status' => rand(1, 10) > 2 ? 'Active' : 'Inactive',
                        'penggunaan' => rand(1, 10) > 8 ? 'NOK' : (rand(1, 10) > 1 ? 'OK' : 'Idle'),
                        'otdr' => rand(1200, 2200),
                        'source_site' => $site['name'],
                        'destination_site' => $sites[($index + 1) % count($sites)]['name'],
                        'keterangan' => "Core {$core} pada Tube {$tube} - " .
                            (rand(0, 1) ? 'Primary service connection' : 'Backup service connection') .
                            '. ' . (rand(1, 10) > 8 ? 'CRITICAL: Perlu maintenance' : 'Performance normal') . '.'
                    ]);
                }
            }
        }

        return redirect()->route('fiber-cores.index')
            ->with('success', 'Sample data berhasil dibuat!');
    }

    /**
     * AJAX search for real-time filtering
     */
    public function search(Request $request)
    {
        $user = Auth::user();

        // Base query with regional filtering
        $query = DB::table('fiber_cores')
            ->select(
                'cable_id',
                'nama_site',
                'region',
                'source_site',
                'destination_site',
                DB::raw('MAX(tube_number) as tube_number'),
                DB::raw('COUNT(*) as total_core')
            );

        // Apply regional filter for non-superadmin users
        if (!$user->isSuperAdmin()) {
            $query->where('region', $user->region);
        }

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('cable_id', 'like', "%{$searchTerm}%")
                    ->orWhere('nama_site', 'like', "%{$searchTerm}%")
                    ->orWhere('region', 'like', "%{$searchTerm}%")
                    ->orWhere('source_site', 'like', "%{$searchTerm}%")
                    ->orWhere('destination_site', 'like', "%{$searchTerm}%");
            });
        }

        // Apply region filter
        if ($request->has('filter_region') && $request->filter_region && $request->filter_region !== 'All') {
            $query->where('region', $request->filter_region);
        }

        $sites = $query->groupBy('cable_id', 'nama_site', 'region', 'source_site', 'destination_site')
            ->orderBy('cable_id')
            ->get();

        return response()->json([
            'html' => view('fiber-cores.partials.sites-table', compact('sites'))->render(),
            'count' => $sites->count()
        ]);
    }
}