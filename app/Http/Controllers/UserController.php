<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FiberCore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        // Check if user is superadmin
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Hanya superadmin yang dapat mengakses halaman ini.');
        }

        $users = User::orderBy('created_at', 'desc')->get();
        $regions = FiberCore::select('region')->distinct()->pluck('region');

        return view('users.index', compact('users', 'regions'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        // Check if user is superadmin
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Hanya superadmin yang dapat mengakses halaman ini.');
        }

        $regions = FiberCore::select('region')->distinct()->pluck('region');
        return view('users.create', compact('regions'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        // Check if user is superadmin
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Hanya superadmin yang dapat mengakses halaman ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:superadmin,regional',
            'region' => 'nullable|string|required_if:role,regional',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'region' => $validated['role'] === 'regional' ? $validated['region'] : null,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        // Check if user is superadmin
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Hanya superadmin yang dapat mengakses halaman ini.');
        }

        $regions = FiberCore::select('region')->distinct()->pluck('region');
        return view('users.edit', compact('user', 'regions'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        // Check if user is superadmin
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Hanya superadmin yang dapat mengakses halaman ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:superadmin,regional',
            'region' => 'nullable|string|required_if:role,regional',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'region' => $validated['role'] === 'regional' ? $validated['region'] : null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Check if user is superadmin
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Hanya superadmin yang dapat mengakses halaman ini.');
        }

        // Prevent deleting own account
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus!');
    }
}