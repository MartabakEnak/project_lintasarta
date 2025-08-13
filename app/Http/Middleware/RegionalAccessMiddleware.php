<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegionalAccessMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Superadmin can access everything
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // For regional users, check if they're trying to access their region's data
        if ($user->isRegionalAdmin()) {
            // Extract cable_id from route if it exists
            $cable_id = $request->route('cable_id') ?? $request->route('fiber_core');

            if ($cable_id) {
                // Check if this cable belongs to user's region
                $fiberCore = \App\Models\FiberCore::where('cable_id', $cable_id)->first();

                if ($fiberCore && !$user->canAccessRegion($fiberCore->region)) {
                    abort(403, 'Anda tidak memiliki akses ke region ini.');
                }
            }

            return $next($request);
        }

        abort(403, 'Akses ditolak.');
    }
}