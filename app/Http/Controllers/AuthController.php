<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() 
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:8',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min'      => 'Kata sandi minimal harus 8 karakter.',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Pulihkan & sinkronisasi keranjang belanja pengguna dari database
            CartController::syncUserCartWithDatabase();

            $user = Auth::user();
            $msg = 'Selamat datang kembali, ' . $user->name . '!';

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', $msg);
            }

            return redirect()->intended(route('home'))->with('success', $msg);
        }

        return back()->withInput($request->only('email', 'remember'))
            ->with('danger', 'Email atau kata sandi yang Anda masukkan tidak sesuai.');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:100|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone'    => 'nullable|string|max:25',
            'address'  => 'nullable|string',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email ini sudah terdaftar. Silakan gunakan email lain atau login.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.min'       => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'address'  => $request->address,
            'role'     => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // Sinkronisasi keranjang belanja jika ada
        CartController::syncUserCartWithDatabase();

        return redirect()->intended(route('home'))
            ->with('success', 'Registrasi berhasil! Selamat datang di The Book Atelier.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar (logout).');
    }
}
