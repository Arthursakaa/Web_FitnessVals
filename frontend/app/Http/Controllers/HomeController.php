<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GymClass;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $userCount = User::count() + 150; // Fake base 150 + real users
        $classCount = GymClass::count();
        $trainerCount = \App\Models\Trainer::count(); // Real trainer count
        $gymClasses = GymClass::take(8)->get();
        
        $imageMap = [
            'BODYPUMP' => 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=400&h=500&fit=crop',
            'ZUMBA' => 'https://images.unsplash.com/photo-1524594152303-9fd13543fe6e?w=400&h=500&fit=crop',
            'BODYCOMBAT' => 'https://images.unsplash.com/photo-1549060279-7e168fcee0c2?w=400&h=500&fit=crop',
            'HATHA YOGA' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=400&h=500&fit=crop',
            'POWER SCULPT' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=400&h=500&fit=crop',
            'HIIT DYNAMICS' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=400&h=500&fit=crop',
            'SPINNING' => 'https://images.unsplash.com/photo-1571902943202-507ec2618e8f?w=400&h=500&fit=crop',
            'PILATES FLOW' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=400&h=500&fit=crop',
        ];
        
        foreach($gymClasses as $c) {
            $c->image = $imageMap[$c->name] ?? 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=400&h=500&fit=crop';
        }
        
        return view('public.home', compact('userCount', 'classCount', 'trainerCount', 'gymClasses'));
    }
}
