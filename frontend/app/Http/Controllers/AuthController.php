<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerProcess(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'plan' => 'required|in:basic,pro,elite',
            'billing_cycle' => 'required|in:monthly,yearly',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'member',
            'plan' => $request->plan,
            'billing_cycle' => $request->billing_cycle,
        ]);

        Auth::login($user);

        return redirect()->route('payment.checkout');
    }

    public function loginProcess(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            try {
                $response = \Illuminate\Support\Facades\Http::post('http://localhost:8080/api/login', [
                    'email' => $request->email,
                    'password' => $request->password,
                ]);
                
                if ($response->successful()) {
                    $request->session()->put('jwt_token', $response->json('token'));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal mendapatkan JWT dari Golang: ' . $e->getMessage());
            }

            return redirect()->intended('dashboard')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');
    }
}
