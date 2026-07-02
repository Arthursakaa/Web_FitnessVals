<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\GymClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '6m');
        $token = $this->getGoJwtToken();

        try {
            $response = \Illuminate\Support\Facades\Http::withToken($token)
                ->get('http://localhost:8080/api/admin/reports');

            if (!$response->successful()) {
                return redirect()->route('admin.login')->with('error', 'Gagal memuat laporan.');
            }
            $data = $response->json();
        } catch (\Exception $e) {
            return redirect()->route('admin.login')->with('error', 'Koneksi ke backend Golang terputus.');
        }

        $totalMembers = $data['totalMembers'] ?? 0;
        $activeMembers = $data['activeMembers'] ?? 0;
        $basicCount = $data['basicCount'] ?? 0;
        $proCount = $data['proCount'] ?? 0;
        $eliteCount = $data['eliteCount'] ?? 0;
        $expiredCount = $data['expiredCount'] ?? 0;
        
        $profiles = $data['profiles'] ?? [];
        $classes = $data['classes'] ?? [];

        // 2. Member Growth (Fake data based on current members for simplicity since we don't have historical creation dates from Go yet)
        $growthLabels = [];
        $growthData = [];
        $monthsToSub = $period == '1y' ? 11 : 5;
        for ($i = $monthsToSub; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $growthLabels[] = $month->format('M');
            // Simplified growth data based on total
            $growthData[] = max(0, $totalMembers - ($i * 5)); 
        }

        // 3. Age Distribution
        $ageDistribution = ['18-24' => 0, '25-34' => 0, '35-44' => 0, '45-54' => 0, '55+' => 0];
        foreach ($profiles as $profile) {
            $age = $profile['age'] ?? 0;
            if ($age >= 18 && $age <= 24) $ageDistribution['18-24']++;
            elseif ($age >= 25 && $age <= 34) $ageDistribution['25-34']++;
            elseif ($age >= 35 && $age <= 44) $ageDistribution['35-44']++;
            elseif ($age >= 45 && $age <= 54) $ageDistribution['45-54']++;
            elseif ($age >= 55) $ageDistribution['55+']++;
        }

        // 4. Membership Distribution
        $pkgLabels = ['Basic', 'Pro', 'Elite'];
        $pkgData = [$basicCount, $proCount, $eliteCount];
        $pkgColors = ['#E5E5E7', '#FF6B2C', '#1C1C1E'];

        // 5. Class Popularity
        $classStats = [];
        $classLabels = [];
        $classData = [];

        foreach ($classes as $class) {
            $schedules = $class['schedules'] ?? [];
            $totalSessions = count($schedules);
            $totalBookings = 0;
            $totalCapacity = 0;
            
            foreach ($schedules as $sch) {
                $totalBookings += count($sch['bookings'] ?? []);
                $totalCapacity += $class['max_capacity'] ?? 20; // Using class max_capacity since schedule capacity isn't fetched
            }
            
            $avgParticipants = $totalSessions > 0 ? round($totalBookings / $totalSessions) : 0;
            $avgCapacity = $totalSessions > 0 ? round($totalCapacity / $totalSessions) : 20;
            $attendanceRate = $totalCapacity > 0 ? round(($totalBookings / $totalCapacity) * 100) : 0;
            $trend = $attendanceRate >= 70 ? 'up' : 'down';

            $classStats[] = [
                'name' => $class['name'],
                'sessions' => $totalSessions,
                'avg' => $avgParticipants . '/' . $avgCapacity,
                'rate' => $attendanceRate . '%',
                'trend' => $trend,
                'attendance_val' => $attendanceRate
            ];
            
            $classLabels[] = $class['name'];
            $classData[] = $attendanceRate;
        }

        usort($classStats, function($a, $b) {
            return $b['attendance_val'] <=> $a['attendance_val'];
        });

        // 6. Top-level stats
        $avgRetention = $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100) . '%' : '0%';
        $churnRate = $totalMembers > 0 ? round(($expiredCount / $totalMembers) * 100, 1) . '%' : '0%';
        
        $totalRevenue = ($basicCount * 299000) + ($proCount * 549000) + ($eliteCount * 899000);
        $revenuePerMember = $totalMembers > 0 ? 'Rp ' . number_format(round($totalRevenue / $totalMembers), 0, ',', '.') : 'Rp 0';

        $revData = [$basicCount * 299000, $proCount * 549000, $eliteCount * 899000];
        $totalRevFormatted = 'Rp ' . number_format($totalRevenue, 0, ',', '.');

        return view('admin.reports', compact(
            'totalMembers', 'activeMembers', 'growthLabels', 'growthData',
            'ageDistribution', 'pkgLabels', 'pkgData', 'pkgColors',
            'classStats', 'classLabels', 'classData',
            'avgRetention', 'churnRate', 'revenuePerMember', 'period',
            'revData', 'totalRevFormatted'
        ));
    }
}
