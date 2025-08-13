<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('fiber-cores.index');
        }

        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('fiber-cores.index'))
                ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak valid.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Show registration form (for testing purposes)
     */
    public function showRegisterForm()
    {
        // Only allow registration if no users exist (for initial setup)
        if (User::count() > 0 && !Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Registrasi tidak diizinkan.');
        }

        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        // Only allow registration if no users exist or user is superadmin
        if (User::count() > 0 && (!Auth::check() || !Auth::user()->isSuperAdmin())) {
            return redirect()->route('login')
                ->with('error', 'Registrasi tidak diizinkan.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:superadmin,regional',
            'region' => 'nullable|string|required_if:role,regional',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'region' => $validated['role'] === 'regional' ? $validated['region'] : null,
        ]);

        if (User::count() === 1) {
            // First user, auto login
            Auth::login($user);
            return redirect()->route('fiber-cores.index')
                ->with('success', 'Akun berhasil dibuat dan Anda telah login!');
        }

        return redirect()->route('login')
            ->with('success', 'Akun berhasil dibuat. Silakan login.');
    }
}