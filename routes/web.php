<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RitualController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PettyCashController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingsController;

// Public routes
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome-new');
})->name('home');

// donation page for general visitors
Route::get('/donations/menu', [DonationController::class, 'menu'])->name('donations.menu');
Route::get('/donate', [DonationController::class, 'publicCreate'])->name('donate');
Route::post('/donate', [DonationController::class, 'publicStore'])->name('donate.store');
Route::get('/donate/thankyou', [DonationController::class, 'thankyou'])->name('donate.thankyou');

// Authentication routes
Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (\Illuminate\Support\Facades\Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
})->middleware('guest')->name('login.post');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

// registration
Route::get('/register', function () {
    return view('auth.register');
})->middleware('guest')->name('register');

Route::post('/register', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|min:6',
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
        'role' => 'member',
    ]);

    // Also create a Member record so user appears on members page
    \App\Models\Member::create([
        'user_id' => $user->id,
        'member_id' => 'MBR-' . date('YmdHis'),
        'join_date' => now(),
        'qr_code_token' => \Illuminate\Support\Str::random(20),
    ]);

    \Illuminate\Support\Facades\Auth::login($user);
    return redirect()->route('dashboard');
})->middleware('guest')->name('register.post');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Roles management
    Route::resource('roles', RoleController::class);

    // Donations
    Route::resource('donations', DonationController::class);
    Route::post('/donations/{donation}/verify', [DonationController::class, 'verify'])->name('donations.verify');
    Route::post('/donations/{donation}/send-receipt', [DonationController::class, 'sendReceipt'])->name('donations.send-receipt');

    // Members
    Route::resource('members', MemberController::class);
    Route::post('/members/{member}/deactivate', [MemberController::class, 'deactivate'])->name('members.deactivate');
    Route::post('/members/{member}/activate', [MemberController::class, 'activate'])->name('members.activate');

    // Rituals/Events
    Route::resource('rituals', RitualController::class);
    Route::post('/rituals/{ritual}/register/{member}', [RitualController::class, 'registerMember'])->name('rituals.register');
    Route::post('/rituals/{ritual}/check-in/{member}', [RitualController::class, 'checkIn'])->name('rituals.check-in');
    Route::post('/rituals/{ritual}/check-out/{member}', [RitualController::class, 'checkOut'])->name('rituals.check-out');

    // Inventory
    Route::resource('inventory', InventoryController::class);
    Route::post('/inventory/{inventoryItem}/adjust-stock', [InventoryController::class, 'adjustStock'])->name('inventory.adjust-stock');

    // Petty Cash
    Route::resource('petty-cash', PettyCashController::class);

    // Reports
    Route::get('/reports/donations', [ReportController::class, 'donations'])->name('reports.donations');
    Route::get('/reports/donations/export', [ReportController::class, 'donationsExport'])->name('reports.donations.export');
    Route::get('/reports/expenses', [ReportController::class, 'expenses'])->name('reports.expenses');
    Route::get('/reports/member-activity', [ReportController::class, 'memberActivity'])->name('reports.member-activity');
    Route::get('/reports/activity-log', [ReportController::class, 'activityLog'])->name('reports.activity-log');
    Route::get('/reports/financial-summary', [ReportController::class, 'financialSummary'])->name('reports.financial-summary');
    Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('/reports/inventory/export', [ReportController::class, 'inventoryExport'])->name('reports.inventory.export');
    Route::get('/reports/schedule', [ReportController::class, 'schedule'])->name('reports.schedule');
    Route::get('/reports/schedule/export', [ReportController::class, 'scheduleExport'])->name('reports.schedule.export');
    
    // Application settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});
