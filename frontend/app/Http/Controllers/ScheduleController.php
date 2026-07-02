<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\ClassBooking;
use App\Events\ClassBooked;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $offset = (int) $request->get('week', 0);
        $startDate = now()->startOfWeek()->addWeeks($offset);
        
        $days = [];
        for ($i=0; $i<7; $i++) {
            $days[] = $startDate->copy()->addDays($i)->format('Y-m-d');
        }

        // Fetch schedules from Go API
        $response = \Illuminate\Support\Facades\Http::withToken($this->getGoJwtToken())->get('http://localhost:8080/api/classes', [
            'start_date' => $startDate->format('Y-m-d H:i:s'),
            'end_date' => $startDate->copy()->endOfWeek()->format('Y-m-d H:i:s')
        ]);
        
        $schedules = $response->successful() ? $response->json() : [];

        // Build unique instructors
        $instructors = collect($schedules)->pluck('trainer_name')->unique()->values()->all();

        // Build matrix [date][time]
        $matrix = [];
        foreach ($schedules as $s) {
            $date = \Carbon\Carbon::parse($s['start_time'])->format('Y-m-d');
            $time = \Carbon\Carbon::parse($s['start_time'])->format('H:i');
            
            // To make it behave like an object in Blade
            $matrix[$date][$time] = json_decode(json_encode($s));
        }

        // Get user's current bookings
        $userBookings = [];
        $myBookings = collect();
        $pastBookings = collect();
        
        if (Auth::check()) {
            $myBookingsRes = \Illuminate\Support\Facades\Http::withToken($this->getGoJwtToken())->get('http://localhost:8080/api/classes/my-bookings');
            
            if ($myBookingsRes->successful()) {
                $bookingsData = $myBookingsRes->json();
                foreach($bookingsData as $b) {
                    $userBookings[] = $b['class_schedule_id'];
                    
                    $sched = json_decode(json_encode($b['class_schedule']));
                    
                    if (\Carbon\Carbon::parse($sched->start_time)->isFuture() || \Carbon\Carbon::parse($sched->start_time)->isToday()) {
                        $myBookings->push($sched);
                    } else {
                        $pastBookings->push($sched);
                    }
                }
                
                // Sort collections
                $myBookings = $myBookings->sortBy('start_time')->values();
                $pastBookings = $pastBookings->sortByDesc('start_time')->values();
            }
        }

        return view('dashboard.schedule', compact('days', 'matrix', 'userBookings', 'myBookings', 'pastBookings', 'offset', 'instructors'));
    }

    public function book(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required',
        ]);

        try {
            $response = \Illuminate\Support\Facades\Http::withToken($this->getGoJwtToken())
                ->post('http://localhost:8080/api/classes/book', [
                    'schedule_id' => (int) $request->schedule_id
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Dispatch Reverb Event for real-time updates on other clients
                // To get the new current_bookings, we would need to fetch the schedule again
                // but since we only need to increment for the UI, let's just trigger a generic update 
                // or we can fetch it. For now we will just assume success.
                return response()->json([
                    'success' => true, 
                    'message' => 'Kelas berhasil dipesan!'
                ]);
            }

            return response()->json([
                'success' => false, 
                'message' => $response->json('error') ?? 'Gagal memesan kelas.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan sistem.'
            ]);
        }
    }

    public function cancelBooking(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required',
        ]);

        try {
            $response = \Illuminate\Support\Facades\Http::withToken($this->getGoJwtToken())
                ->post('http://localhost:8080/api/classes/cancel', [
                    'schedule_id' => (int) $request->schedule_id
                ]);

            if ($response->successful()) {
                // We dispatch a generic ClassBooked event to decrement for others if we knew the new count.
                // For now, assume it's successful.
                return response()->json([
                    'success' => true, 
                    'message' => 'Booking kelas berhasil dibatalkan.'
                ]);
            }

            return response()->json([
                'success' => false, 
                'message' => $response->json('error') ?? 'Gagal membatalkan kelas.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan sistem.'
            ]);
        }
    }
}
