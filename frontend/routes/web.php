<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/membership', fn() => view('public.membership'))->name('membership');
Route::get('/classes', function() {
    $classes = \App\Models\GymClass::all();
    
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
    
    foreach($classes as $c) {
        $c->image = $imageMap[$c->name] ?? 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=400&h=500&fit=crop';
        $c->difficulty = in_array($c->type, ['Strength', 'Cardio', 'HIIT']) ? 'Hard' : (in_array($c->type, ['Yoga', 'Dance']) ? 'Easy' : 'Medium');
        $c->css_class = strtolower(str_replace([' ', '&'], ['-', ''], $c->type));
    }

    return view('public.classes', compact('classes'));
})->name('classes');
Route::get('/classes/{id}', function($id) {
    $class = \App\Models\GymClass::findOrFail($id);
    
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
    
    $class->image = str_replace('w=400&h=500', 'w=800&h=500', $imageMap[$class->name] ?? 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&h=500&fit=crop');
    $class->difficulty = in_array($class->type, ['Strength', 'Cardio', 'HIIT']) ? 'Hard' : (in_array($class->type, ['Yoga', 'Dance']) ? 'Easy' : 'Medium');

    return view('public.class-detail', compact('class'));
})->name('class.detail');
Route::get('/trainers', function() {
    $trainers = \App\Models\Trainer::all();
    return view('public.trainers', compact('trainers'));
})->name('trainers');
Route::get('/trainers/{id}', function($id) {
    $trainer = \App\Models\Trainer::findOrFail($id);
    return view('public.trainer-detail', compact('trainer'));
})->name('trainer.detail');
Route::get('/about', fn() => view('public.about'))->name('about');
Route::get('/contact', fn() => view('public.contact'))->name('contact');
Route::get('/register', fn() => view('auth.register'))->name('register');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'registerProcess'])->name('register.process');
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'loginProcess'])->name('login.process');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/payment/checkout', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/process', [\App\Http\Controllers\PaymentController::class, 'process'])->name('payment.process');
});

/*
|--------------------------------------------------------------------------
| Member Dashboard
|--------------------------------------------------------------------------
*/

// TEST REVERB ROUTE
Route::get('/test-booking/{scheduleId}', function ($scheduleId) {
    // Simulate booking
    $currentBookings = rand(1, 19); 
    $maxCapacity = 20;
    
    event(new \App\Events\ClassBooked($scheduleId, $currentBookings, $maxCapacity));
    
    return "Event ClassBooked dispatched for Schedule ID: {$scheduleId} with {$currentBookings}/{$maxCapacity} bookings!";
});

Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\MemberDashboardController::class, 'index'])->name('home');
    Route::get('/progress', [\App\Http\Controllers\ProgressController::class, 'index'])->name('progress');
    Route::post('/progress/log', [\App\Http\Controllers\ProgressController::class, 'storeLog'])->name('progress.log');
    Route::post('/progress/plan', [\App\Http\Controllers\ProgressController::class, 'storePlan'])->name('progress.plan');
    Route::get('/nutrition', [\App\Http\Controllers\NutritionController::class, 'index'])->name('nutrition');
    Route::post('/nutrition/log', [\App\Http\Controllers\NutritionController::class, 'storeLog'])->name('nutrition.log');
    Route::get('/meal-recommendations', [\App\Http\Controllers\NutritionController::class, 'meals'])->name('meals');
    Route::get('/schedule', [\App\Http\Controllers\ScheduleController::class, 'index'])->name('schedule');
    Route::post('/schedule/book', [\App\Http\Controllers\ScheduleController::class, 'book'])->name('schedule.book');
    Route::post('/schedule/cancel', [\App\Http\Controllers\ScheduleController::class, 'cancelBooking'])->name('schedule.cancel');
    Route::get('/trainers', [\App\Http\Controllers\TrainerController::class, 'index'])->name('trainers');
    Route::post('/trainers/book', [\App\Http\Controllers\TrainerController::class, 'book'])->name('trainers.book');
    Route::get('/bmi', [\App\Http\Controllers\BmiController::class, 'index'])->name('bmi');
    Route::post('/bmi', [\App\Http\Controllers\BmiController::class, 'store'])->name('bmi.store');
    
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/health', [\App\Http\Controllers\ProfileController::class, 'updateHealth'])->name('profile.health');
    Route::post('/onboarding', [\App\Http\Controllers\ProfileController::class, 'storeOnboarding'])->name('onboarding.store');
});

/*
|--------------------------------------------------------------------------
| Admin Panel
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => view('admin.login'))->name('login');
    Route::post('/', [\App\Http\Controllers\Admin\AdminAuthController::class, 'loginProcess'])->name('login.process');
    Route::post('/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');
    
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/members/export', [\App\Http\Controllers\Admin\AdminMemberController::class, 'exportCsv'])->name('members.export');
        Route::delete('/members/bulk', [\App\Http\Controllers\Admin\AdminMemberController::class, 'bulkDestroy'])->name('members.bulk_destroy');
        Route::get('/members', [\App\Http\Controllers\Admin\AdminMemberController::class, 'index'])->name('members');
        Route::get('/members/{id}', [\App\Http\Controllers\Admin\AdminMemberController::class, 'show'])->name('member.detail');
        Route::delete('/members/{id}', [\App\Http\Controllers\Admin\AdminMemberController::class, 'destroy'])->name('members.destroy');
        
        Route::get('/classes', [\App\Http\Controllers\Admin\AdminClassController::class, 'index'])->name('classes');
        Route::post('/classes', [\App\Http\Controllers\Admin\AdminClassController::class, 'store'])->name('classes.store');
        Route::put('/classes/{id}', [\App\Http\Controllers\Admin\AdminClassController::class, 'update'])->name('classes.update');
        Route::delete('/classes/{id}', [\App\Http\Controllers\Admin\AdminClassController::class, 'destroy'])->name('classes.destroy');
        
        Route::get('/trainers', [\App\Http\Controllers\Admin\AdminTrainerController::class, 'index'])->name('trainers');
        Route::post('/trainers', [\App\Http\Controllers\Admin\AdminTrainerController::class, 'store'])->name('trainers.store');
        Route::put('/trainers/{id}', [\App\Http\Controllers\Admin\AdminTrainerController::class, 'update'])->name('trainers.update');
        Route::delete('/trainers/{id}', [\App\Http\Controllers\Admin\AdminTrainerController::class, 'destroy'])->name('trainers.destroy');
        
        Route::get('/content', fn() => view('admin.content'))->name('content');
        Route::get('/appearance', fn() => view('admin.appearance'))->name('appearance');
        Route::get('/features', fn() => view('admin.features'))->name('features');
        Route::get('/settings', fn() => view('admin.settings'))->name('settings');
        Route::post('/settings/update', [\App\Http\Controllers\Admin\AdminSettingController::class, 'update'])->name('settings.update');
        Route::get('/reports', [\App\Http\Controllers\Admin\AdminReportController::class, 'index'])->name('reports');
    });
});
