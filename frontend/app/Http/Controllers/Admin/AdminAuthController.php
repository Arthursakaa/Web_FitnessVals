<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function loginProcess(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            if (Auth::user()->role === 'admin') {
                try {
                    $response = \Illuminate\Support\Facades\Http::post('http://localhost:8080/api/login', [
                        'email' => $request->email,
                        'password' => $request->password,
                    ]);
                    
                    if ($response->successful()) {
                        $request->session()->put('jwt_token', $response->json('token'));
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Gagal mendapatkan JWT dari Golang (Admin): ' . $e->getMessage());
                }

                return redirect()->route('admin.dashboard');
            }

            Auth::logout();
            return back()->with('error', 'Akses ditolak. Anda bukan Administrator.');
        }

        return back()->withErrors([
            'email' => 'Email atau Password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
