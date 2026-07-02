<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Trainer;
use App\Models\TrainerBooking;
use Illuminate\Support\Facades\Auth;

class TrainerController extends Controller
{
    public function index()
    {
        $trainers = [];
        $myBookings = collect();

        if (Auth::check()) {
            $trainersRes = \Illuminate\Support\Facades\Http::withToken($this->getGoJwtToken())->get('http://localhost:8080/api/trainers');
            if ($trainersRes->successful()) {
                $trainers = json_decode(json_encode($trainersRes->json()));
            }

            $bookingsRes = \Illuminate\Support\Facades\Http::withToken($this->getGoJwtToken())->get('http://localhost:8080/api/trainers/my-bookings');
            if ($bookingsRes->successful()) {
                $bookingsData = $bookingsRes->json();
                foreach($bookingsData as $b) {
                    $myBookings->push(json_decode(json_encode($b)));
                }
                $myBookings = $myBookings->sortByDesc('booking_date')->values();
            }
        }

        return view('dashboard.trainers', compact('trainers', 'myBookings'));
    }

    public function book(Request $request)
    {
        $request->validate([
            'trainer_id' => 'required',
            'booking_date' => 'required|date',
            'booking_time' => 'required|string',
            'session_type' => 'required|string',
            'message' => 'nullable|string'
        ]);

        try {
            $response = \Illuminate\Support\Facades\Http::withToken($this->getGoJwtToken())
                ->post('http://localhost:8080/api/trainers/book', [
                    'trainer_id' => (int) $request->trainer_id,
                    'booking_date' => $request->booking_date,
                    'booking_time' => $request->booking_time,
                    'session_type' => $request->session_type,
                    'message' => $request->message ?? ''
                ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Booking berhasil! Menunggu konfirmasi dari trainer.']);
            }

            return response()->json([
                'success' => false, 
                'message' => $response->json('error') ?? 'Gagal memesan trainer.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan sistem.'
            ]);
        }
    }
}
