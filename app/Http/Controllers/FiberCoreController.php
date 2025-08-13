<?php

namespace App\Http\Controllers;

use App\Models\FiberCore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FiberCoreController extends Controller
{
    /**
     * Display a listing of fiber cores
     */
    public function index(Request $request)
    {
        // Base query untuk sites
        $query = DB::table('fiber_cores')
            ->select(
                'cable_id',
                'nama_site',
                'region',
                'source_site',
                'destination_site',
                'otdr',
                DB::raw('MAX(tube_number) as tube_number'),
                DB::raw('COUNT(*) as total_core')
            );

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('cable_id', 'LIKE', "%{$search}%")
                  ->orWhere('nama_site', 'LIKE', "%{$search}%")
                  ->orWhere('region', 'LIKE', "%{$search}%")
                  ->orWhere('source_site', 'LIKE', "%{$search}%")
                  ->orWhere('destination_site', 'LIKE', "%{$search}%")
                  ->orWhere('keterangan', 'LIKE', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('filter_status') && $request->filter_status !== 'All') {
            $query->where('status', $request->filter_status);
        }

        // Apply region filter
        if ($request->filled('filter_region') && $request->filter_region !== 'All') {
            $query->where('region', $request->filter_region);
        }

        $sites = $query->groupBy('cable_id', 'nama_site', 'region', 'source_site', 'destination_site', 'otdr')
                      ->orderBy('cable_id')
                      ->paginate(10)
                      ->withQueryString(); // Preserve query parameters in pagination

        // Query statistik (tetap tanpa filter untuk overview keseluruhan)
        $stats = [
            'total' => FiberCore::count(),
            'active' => FiberCore::where('status', 'Active')->count(),
            'inactive' => FiberCore::where('status', 'Inactive')->count(),
            'problems' => FiberCore::where('penggunaan', 'NOK')->count(),
        ];

        // Regional stats (tetap tanpa filter)
        $regionalStats = DB::table('fiber_cores')
            ->select(
                'region',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "Active" THEN 1 ELSE 0 END) as active'),
                DB::raw('SUM(CASE WHEN penggunaan = "NOK" THEN 1 ELSE 0 END) as problems')
            )
            ->groupBy('region')
            ->orderBy('region')
            ->get();

        $regions = FiberCore::select('region')->distinct()->pluck('region');

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
        $validated = $request->validate([
            'cable_id' => 'required|string|max:255|',
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
        // Ambil semua core dengan cable_id ini
        $cores = FiberCore::where('cable_id', $cable_id)
            ->orderBy('tube_number')
            ->orderBy('core')
            ->get();

        // Ambil info site (ambil satu saja)
        $site = $cores->first();

        return view('fiber-cores.show', compact('site', 'cores'));
    }

    /**
     * Show the form for editing the specified fiber core
     */
    public function edit(FiberCore $fiberCore)
    {
        return view('fiber-cores.edit', compact('fiberCore'));
    }

    /**
     * Update the specified fiber core
     */
    public function update(Request $request, $cable_id, $id)
    {
        $core = FiberCore::where('cable_id', $cable_id)->where('id', $id)->firstOrFail();

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
        $fiberCore->delete();

        return redirect()->route('fiber-cores.index')
            ->with('success', 'Core fiber berhasil dihapus!');
    }

    /**
     * Remove all cores for a specific cable_id
     */
    public function destroyByCableId($cable_id)
    {
        // Get site info before deletion
        $site = FiberCore::where('cable_id', $cable_id)->first();

        if (!$site) {
            return redirect()->route('fiber-cores.index')
                ->with('error', 'Cable ID tidak ditemukan!');
        }

        // Count cores to be deleted
        $coreCount = FiberCore::where('cable_id', $cable_id)->count();

        // Delete all cores with this cable_id
        FiberCore::where('cable_id', $cable_id)->delete();

        return redirect()->route('fiber-cores.index')
            ->with('success', "Berhasil menghapus {$coreCount} core dari Cable ID: {$cable_id} ({$site->nama_site})");
    }

    /**
     * Generate sample data (for testing purposes)
     */
    public function generateSample()
    {
        $sites = [
            ['name' => 'Teuku Umar', 'region' => 'Denpasar Utara'],
            ['name' => 'Sanur Beach', 'region' => 'Denpasar Selatan'],
            ['name' => 'Ubud Central', 'region' => 'Gianyar'],
            ['name' => 'Kuta Square', 'region' => 'Badung'],
            ['name' => 'Ngurah Rai Airport', 'region' => 'Badung'],
            ['name' => 'Canggu Beach', 'region' => 'Badung'],
            ['name' => 'Seminyak Center', 'region' => 'Badung'],
            ['name' => 'Tabanan Hub', 'region' => 'Tabanan'],
            ['name' => 'Klungkung Central', 'region' => 'Klungkung'],
            ['name' => 'Singaraja North', 'region' => 'Buleleng']
        ];

        // Generate sample data for multiple sites
        foreach ($sites as $index => $site) {
            if ($index >= 3) break; // Hanya 3 site pertama

            $cable_id = 'CABLE-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

            for ($tube = 1; $tube <= rand(2, 4); $tube++) {
                for ($core = 1; $core <= rand(6, 12); $core++) {
                    FiberCore::create([
                        'cable_id' => $cable_id,
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
}
