<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = \App\Models\UserProfile::where('user_id', $user->id)->first();
        return view('dashboard.profile', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updateHealth(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'height_cm' => 'required|numeric',
            'weight_kg' => 'required|numeric',
            'target_weight_kg' => 'nullable|numeric',
            'dietary_preference' => 'nullable|string',
            'medical_history' => 'nullable|array',
        ]);

        $profile = \App\Models\UserProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['gender' => 'male', 'age' => 25] // default dummy age/gender if not exists
        );

        $profile->height_cm = $request->height_cm;
        $profile->weight_kg = $request->weight_kg;
        $profile->target_weight_kg = $request->target_weight_kg;
        $profile->dietary_preference = $request->dietary_preference;
        
        // Remove 'Tidak Ada' if other conditions are selected
        $medHist = $request->medical_history ?? [];
        if (count($medHist) > 1 && in_array('Tidak Ada', $medHist)) {
            $medHist = array_diff($medHist, ['Tidak Ada']);
        }
        
        // Add casts to UserProfile model if not added already. Wait, let's just use json_encode if not casted.
        $profile->medical_history = array_values($medHist);
        $profile->save();

        return redirect()->back()->with('success', 'Data kesehatan berhasil diperbarui!');
    }

    public function storeOnboarding(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'gender' => 'required|in:male,female',
            'age' => 'required|numeric|min:1',
            'height_cm' => 'required|numeric|min:50',
            'weight_kg' => 'required|numeric|min:20',
            'fitness_goal' => 'nullable|string',
            'activity_level_multiplier' => 'nullable|numeric',
            'dietary_preference' => 'nullable|string',
            'medical_history' => 'nullable|array',
        ]);

        $profile = \App\Models\UserProfile::firstOrCreate(
            ['user_id' => $user->id]
        );

        $profile->gender = $request->gender;
        $profile->age = $request->age;
        $profile->height_cm = $request->height_cm;
        $profile->weight_kg = $request->weight_kg;
        $profile->fitness_goal = $request->fitness_goal;
        
        if ($request->filled('activity_level_multiplier')) {
            $profile->activity_level_multiplier = $request->activity_level_multiplier;
        }

        $profile->dietary_preference = $request->dietary_preference ?? 'Normal';
        
        $medHist = $request->medical_history ?? [];
        if (count($medHist) > 1 && in_array('Tidak Ada', $medHist)) {
            $medHist = array_diff($medHist, ['Tidak Ada']);
        }
        if (empty($medHist)) {
            $medHist = ['Tidak Ada'];
        }
        
        $profile->medical_history = array_values($medHist);
        $profile->save();

        return redirect()->route('dashboard.home')->with('success', 'Onboarding berhasil! Profil Anda sudah lengkap.');
    }
}
