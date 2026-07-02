<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function checkout()
    {
        $user = Auth::user();
        
        // Define pricing based on UI
        $prices = [
            'basic' => ['monthly' => 299000, 'yearly' => 2868000],
            'pro' => ['monthly' => 549000, 'yearly' => 5268000],
            'elite' => ['monthly' => 899000, 'yearly' => 8628000],
        ];

        $plan = $user->plan ?? 'pro';
        $cycle = $user->billing_cycle ?? 'monthly';
        
        $amount = $prices[$plan][$cycle] ?? 549000;
        
        // Add unique code for realism
        $uniqueCode = rand(100, 999);
        $totalAmount = $amount + $uniqueCode;

        return view('public.payment', compact('user', 'plan', 'cycle', 'amount', 'uniqueCode', 'totalAmount'));
    }

    public function process(Request $request)
    {
        // Simulation delay if needed or handle purely via JS
        // We'll handle animation purely via JS in the view and then hit this endpoint
        return response()->json(['status' => 'success', 'message' => 'Payment simulated successfully']);
    }
}
