<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthCountroller extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'nullable|string'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user', // default role
        ]);

        Auth::login($user);

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->is_locked) {
            return back()->withErrors([
                'email' => 'Your account is blocked. Please contact admin.'
            ]);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Mark worker online using email
            if ($user->worker) {
                $user->worker->update(['online' => true]);
            }

            // Redirect based on role
            $role = $user->role;

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'super_admin') {
                return redirect()->route('super.dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        }


        if ($user) {
            $attempts = $user->login_attempts + 1; // predict new value
            $user->increment('login_attempts');

            if ($attempts >= 3) {
                $user->update(['is_locked' => true]);
                return back()->withErrors([
                    'email' => 'Your account is blocked after 3 failed attempts.'
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Invalid Credentials'
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        // Set worker offline if exists
        if ($user && $user->worker) {
            $user->worker->update(['online' => false]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
