<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GymClass;
use App\Models\ClassBooking;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $token = $this->getGoJwtToken();

        try {
            $response = \Illuminate\Support\Facades\Http::withToken($token)
                ->get('http://localhost:8080/api/admin/dashboard');

            if ($response->successful()) {
                $data = $response->json();
                
                $memberCount = $data['memberCount'] ?? 0;
                $classCount = $data['classCount'] ?? 0;
                $bookingCount = $data['bookingCount'] ?? 0;
                
                $basicCount = $data['basicCount'] ?? 0;
                $proCount = $data['proCount'] ?? 0;
                $eliteCount = $data['eliteCount'] ?? 0;
                
                $recentMembers = $data['recentMembers'] ?? [];
                
                $monthlyRevenue = $data['monthlyRevenue'] ?? 0;

                return view('admin.dashboard', compact('memberCount', 'classCount', 'bookingCount', 'monthlyRevenue', 'basicCount', 'proCount', 'eliteCount', 'recentMembers'));
            } else {
                return redirect()->route('admin.login')->with('error', 'Gagal memuat dashboard: ' . $response->body());
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.login')->with('error', 'Koneksi ke backend Golang terputus.');
        }
    }
}
