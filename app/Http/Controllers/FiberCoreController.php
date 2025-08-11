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
        $query = FiberCore::query();

        // Apply filters
        $query->search($request->search)
            ->byStatus($request->filter_status)
            ->byRegion($request->filter_region);

        // Get paginated results
        $cores = $query->orderBy('nama_site')
            ->orderBy('tube_number')
            ->orderBy('core')
            ->paginate(20)
            ->withQueryString();

        // Get statistics
        $stats = $this->getStatistics();

        // Get regional statistics
        $regionalStats = $this->getRegionalStatistics();

        // Get unique regions for filter dropdown
        $regions = FiberCore::distinct('region')->pluck('region')->sort();

        return view('fiber-cores.index', compact(
            'cores',
            'stats',
            'regionalStats',
            'regions'
        ));
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
            'nama_site' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'tube_number' => 'required|integer|min:1',
            'core' => 'required|integer|min:1|max:12',
            'status' => 'required|in:Active,Inactive',
            'penggunaan' => 'required|in:OK,NOK,Idle',
            'otdr' => 'required|integer|min:0',
            'source_site' => 'required|string|max:255',
            'destination_site' => 'required|string|max:255',
            'keterangan' => 'nullable|string'
        ]);

        // Check for duplicate core in same site and tube
        $exists = FiberCore::where('nama_site', $validated['nama_site'])
            ->where('tube_number', $validated['tube_number'])
            ->where('core', $validated['core'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'core' => 'Core ' . $validated['core'] . ' pada Tube ' . $validated['tube_number'] . ' di site ini sudah ada.'
            ])->withInput();
        }

        FiberCore::create($validated);

        return redirect()->route('fiber-cores.index')
            ->with('success', 'Core fiber berhasil ditambahkan!');
    }

    /**
     * Display the specified fiber core
     */
    public function show(FiberCore $fiberCore)
    {
        return view('fiber-cores.show', compact('fiberCore'));
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
    public function update(Request $request, FiberCore $fiberCore)
    {
        $validated = $request->validate([
            'nama_site' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'tube_number' => 'required|integer|min:1',
            'core' => 'required|integer|min:1|max:12',
            'status' => 'required|in:Active,Inactive',
            'penggunaan' => 'required|in:OK,NOK,Idle',
            'otdr' => 'required|integer|min:0',
            'source_site' => 'required|string|max:255',
            'destination_site' => 'required|string|max:255',
            'keterangan' => 'nullable|string'
        ]);

        // Check for duplicate core (excluding current record)
        $exists = FiberCore::where('nama_site', $validated['nama_site'])
            ->where('tube_number', $validated['tube_number'])
            ->where('core', $validated['core'])
            ->where('id', '!=', $fiberCore->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'core' => 'Core ' . $validated['core'] . ' pada Tube ' . $validated['tube_number'] . ' di site ini sudah ada.'
            ])->withInput();
        }

        $fiberCore->update($validated);

        return redirect()->route('fiber-cores.index')
            ->with('success', 'Core fiber berhasil diperbarui!');
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
     * Get overall statistics
     */
    private function getStatistics()
    {
        return [
            'total' => FiberCore::count(),
            'active' => FiberCore::active()->count(),
            'inactive' => FiberCore::inactive()->count(),
            'problems' => FiberCore::problems()->count()
        ];
    }

    /**
     * Get regional statistics
     */
    private function getRegionalStatistics()
    {
        return DB::table('fiber_cores')
            ->select(
                'region',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "Active" THEN 1 ELSE 0 END) as active'),
                DB::raw('SUM(CASE WHEN penggunaan = "NOK" THEN 1 ELSE 0 END) as problems')
            )
            ->groupBy('region')
            ->orderBy('region')
            ->get();
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

        // Generate 2 tubes with 12 cores each for first site
        $site = $sites[0];

        for ($tube = 1; $tube <= 2; $tube++) {
            for ($core = 1; $core <= 12; $core++) {
                FiberCore::create([
                    'nama_site' => $site['name'],
                    'region' => $site['region'],
                    'tube_number' => $tube,
                    'core' => $core,
                    'status' => rand(1, 10) > 2 ? 'Active' : 'Inactive',
                    'penggunaan' => rand(1, 10) > 8 ? 'NOK' : (rand(1, 10) > 1 ? 'OK' : 'Idle'),
                    'otdr' => rand(1200, 2200),
                    'source_site' => $site['name'],
                    'destination_site' => $sites[1]['name'],
                    'keterangan' => "Core {$core} pada Tube {$tube} - " .
                        (rand(0, 1) ? 'Primary service connection' : 'Backup service connection') .
                        '. ' . (rand(1, 10) > 8 ? 'CRITICAL: Perlu maintenance' : 'Performance normal') . '.'
                ]);
            }
        }

        return redirect()->route('fiber-cores.index')
            ->with('success', 'Sample data berhasil dibuat!');
    }
}